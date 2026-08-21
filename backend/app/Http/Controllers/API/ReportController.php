<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use App\Models\DuplicateGroup;
use App\Models\MergeLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function cleanedPriceList()
    {
        $records = PriceList::where('status', 'active')->get();
        return $this->exportCsv($records, 'cleaned_price_list.csv');
    }

    public function duplicates()
    {
        $records = PriceList::where('status', 'duplicate')->get();
        return $this->exportCsv($records, 'duplicates_report.csv');
    }

    public function rejectedDuplicates()
    {
        $groups = DuplicateGroup::where('status', 'ignored')->with('items.priceList')->get();
        $records = collect();
        foreach ($groups as $group) {
            foreach ($group->items as $item) {
                $records->push($item->priceList);
            }
        }
        return $this->exportCsv($records, 'rejected_duplicates.csv');
    }

    public function mergeHistory()
    {
        $logs = MergeLog::with(['group', 'canonicalPriceList', 'mergedBy'])->orderBy('created_at', -1)->get();
        
        $headers = ['ID', 'Group Code', 'Canonical PL', 'Merged IDs', 'Merged By', 'Date', 'Notes'];
        
        $callback = function() use ($logs, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->group ? $log->group->group_code : 'N/A',
                    $log->canonicalPriceList ? $log->canonicalPriceList->pl_number_original : 'N/A',
                    implode(', ', $log->merged_price_list_ids ?? []),
                    $log->mergedBy ? $log->mergedBy->name : 'System',
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->notes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="merge_history.csv"',
        ]);
    }

    protected function exportCsv($records, $filename)
    {
        $headers = [
            'ID', 'Original PL', 'Normalized PL', 'Item Name', 'Vendor Name', 'Price', 'Currency', 'Effective Date', 'Status', 'Created At'
        ];

        $callback = function() use ($records, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            
            foreach ($records as $record) {
                fputcsv($file, [
                    $record->id,
                    $record->pl_number_original,
                    $record->pl_number_normalized,
                    $record->item_name,
                    $record->vendor_name,
                    $record->price,
                    $record->currency,
                    $record->effective_date,
                    $record->status,
                    $record->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
