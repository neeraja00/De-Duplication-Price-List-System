<?php

namespace App\Http\Controllers;

use App\Models\MergeLog;

class MergeLogController extends Controller
{
    public function index()
    {
        $logs = MergeLog::where('merged_by', auth()->id())->with('mergedBy', 'group')->orderBy('created_at', -1)->paginate(50);
        return view('merge_logs.index', compact('logs'));
    }
}
