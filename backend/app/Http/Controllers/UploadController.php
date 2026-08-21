<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadedFile;
use App\Models\PriceList;
use App\Models\RawUploadRow;
use App\Models\ImportProcessingLog;
use App\Services\DeduplicationService;
use Exception;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    protected $deduplicationService;

    public function __construct(DeduplicationService $deduplicationService)
    {
        $this->deduplicationService = $deduplicationService;
    }

    public function create()
    {
        $uploads = UploadedFile::where('user_id', auth()->id())->with('user')->orderBy('created_at', -1)->get();
        return view('upload.index', compact('uploads'));
    }

    public function store(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = $file->store('uploads');

        $uploadedFile = UploadedFile::create([
            'user_id' => Auth()->id(),
            'original_filename' => $originalName,
            'stored_filename' => $storedName,
            'status' => 'processing',
        ]);

        try {
            $path = Storage::path($storedName);
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data);
            
            $importedCount = 0;
            $now = now();

            $rawUploadRows = [];
            $priceListRows = [];

            foreach ($data as $row) {
                if (count($row) >= 6) {
                    $rawUploadRows[] = [
                        'uploaded_file_id' => $uploadedFile->id,
                        'raw_data' => $row,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $priceListRows[] = [
                        'uploaded_file_id' => $uploadedFile->id,
                        'pl_number_original' => $row[0],
                        'pl_number_normalized' => '', // populated by service
                        'item_name' => $row[1],
                        'vendor_name' => $row[2],
                        'price' => is_numeric($row[3]) ? (float)$row[3] : null,
                        'currency' => $row[4],
                        'effective_date' => date('Y-m-d', strtotime($row[5])) ?: null,
                        'status' => 'active',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $importedCount++;
                }
            }

            if (!empty($rawUploadRows)) {
                foreach (array_chunk($rawUploadRows, 100) as $chunk) {
                    RawUploadRow::insert($chunk);
                }
            }

            if (!empty($priceListRows)) {
                foreach (array_chunk($priceListRows, 100) as $chunk) {
                    PriceList::insert($chunk);
                }
            }

            $uploadedFile->total_records = $importedCount;
            $uploadedFile->imported_records = $importedCount;
            $uploadedFile->save();

            $this->deduplicationService->processUpload($uploadedFile);
            $uploadedFile->refresh();

            ImportProcessingLog::create([
                'uploaded_file_id' => $uploadedFile->id,
                'total_records_processed' => $importedCount,
                'status' => 'success',
                'completed_at' => now(),
            ]);

            \App\Services\AuditLogger::log('file_uploaded', 'import', 'success', 'Price list file uploaded and processed successfully', [
                'uploaded_file_id' => (string) $uploadedFile->id,
                'original_filename' => $originalName,
                'stored_filename' => $storedName,
                'total_records' => $importedCount,
                'imported_records' => $importedCount,
                'duplicate_records' => $uploadedFile->duplicate_records ?? 0
            ]);

            if (($uploadedFile->duplicate_records ?? 0) > 0) {
                // Determine involved group IDs
                $priceListIds = \App\Models\PriceList::where('uploaded_file_id', $uploadedFile->id)->pluck('id');
                $groupIds = \App\Models\DuplicateGroupItem::whereIn('price_list_id', $priceListIds)->pluck('duplicate_group_id')->unique();
                
                \App\Services\AuditLogger::log('duplicates_detected', 'deduplication', 'success', 'Duplicate groups generated after file upload', [
                    'uploaded_file_id' => (string) $uploadedFile->id,
                    'duplicate_group_count' => $groupIds->count(),
                    'duplicate_record_count' => $uploadedFile->duplicate_records,
                    'match_types_detected' => \App\Models\DuplicateGroup::whereIn('id', $groupIds)->pluck('match_type')->unique()->values()->toArray()
                ]);
            }

            return back()->with('success', 'File processed successfully.');
        } catch (Exception $e) {
            $uploadedFile->status = 'failed';
            $uploadedFile->save();
            
            ImportProcessingLog::create([
                'uploaded_file_id' => $uploadedFile->id,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'failed_at' => now(),
            ]);

            \App\Services\AuditLogger::log('file_upload_failed', 'import', 'failed', 'Price list file upload failed', [
                'original_filename' => $originalName,
                'error_message' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to process file: ' . $e->getMessage());
        }
    }
}
