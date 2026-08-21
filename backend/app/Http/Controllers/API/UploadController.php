<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UploadedFile;
use App\Models\PriceList;
use App\Services\DeduplicationService;
use Illuminate\Support\Facades\Storage;
use Exception;

class UploadController extends Controller
{
    protected $deduplicationService;

    public function __construct(DeduplicationService $deduplicationService)
    {
        $this->deduplicationService = $deduplicationService;
    }

    public function index()
    {
        return response()->json(UploadedFile::with('user')->orderBy('created_at', -1)->get());
    }

    public function show($id)
    {
        return response()->json(UploadedFile::with('user')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB limit
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = $file->store('uploads');

        $uploadedFile = UploadedFile::create([
            'user_id' => $request->user()->id,
            'original_filename' => $originalName,
            'stored_filename' => $storedName,
            'status' => 'processing',
        ]);

        try {
            $path = Storage::path($storedName);
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data);
            
            $importedCount = 0;
            
            foreach ($data as $row) {
                if (count($row) >= 6) {
                    PriceList::create([
                        'uploaded_file_id' => $uploadedFile->id,
                        'pl_number_original' => $row[0],
                        'pl_number_normalized' => '', // Updated in service
                        'item_name' => $row[1],
                        'vendor_name' => $row[2],
                        'price' => is_numeric($row[3]) ? $row[3] : null,
                        'currency' => $row[4],
                        'effective_date' => date('Y-m-d', strtotime($row[5])) ?: null,
                        'status' => 'active',
                    ]);
                    $importedCount++;
                }
            }

            $uploadedFile->total_records = $importedCount;
            $uploadedFile->imported_records = $importedCount;
            $uploadedFile->save();

            $this->deduplicationService->processUpload($uploadedFile);

            return response()->json([
                'message' => 'File uploaded and processed successfully',
                'upload' => $uploadedFile
            ]);

        } catch (Exception $e) {
            $uploadedFile->status = 'failed';
            $uploadedFile->save();
            return response()->json(['message' => 'Failed to process file: ' . $e->getMessage()], 500);
        }
    }
}
