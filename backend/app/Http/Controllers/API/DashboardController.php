<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UploadedFile;
use App\Models\PriceList;
use App\Models\DuplicateGroup;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_uploads' => UploadedFile::count(),
            'total_records' => PriceList::count(),
            'total_duplicates' => DuplicateGroup::count(),
            'exact_duplicates' => DuplicateGroup::where('match_type', 'exact')->count(),
            'formatting_duplicates' => DuplicateGroup::where('match_type', 'formatting')->count(),
            'typo_duplicates' => DuplicateGroup::where('match_type', 'typo')->count(),
            'pending_review' => DuplicateGroup::where('status', 'pending')->count(),
            'resolved' => DuplicateGroup::where('status', 'resolved')->count(),
            'rejected' => DuplicateGroup::where('status', 'ignored')->count(),
        ];

        $chartData = UploadedFile::selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', -1)
            ->take(7)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'stats' => $stats,
            'chart_data' => $chartData
        ]);
    }
}
