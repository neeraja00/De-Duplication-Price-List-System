<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Models\PriceList;
use App\Models\DuplicateGroup;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $stats = [
                'active_users' => \App\Models\User::where('last_seen_at', '>=', now()->subMinutes(10))->count(),
                'total_users' => \App\Models\User::count(),
                'total_uploads' => UploadedFile::count(),
                'total_records' => PriceList::count(),
                'total_duplicates' => DuplicateGroup::count(),
                'pending_review' => DuplicateGroup::where('status', 'pending')->count(),
                'resolved' => DuplicateGroup::where('status', 'resolved')->count(),
            ];
            $recentLogs = \App\Models\AuditLog::orderBy('created_at', 'desc')->take(10)->get();
            return view('dashboard', compact('stats', 'recentLogs'));
        } else {
            $userFileIds = UploadedFile::where('user_id', auth()->id())->pluck('id');
            $stats = [
                'total_uploads' => UploadedFile::where('user_id', auth()->id())->count(),
                'total_records' => PriceList::whereIn('uploaded_file_id', $userFileIds)->count(),
                'total_duplicates' => DuplicateGroup::where('user_id', auth()->id())->count(),
                'pending_review' => DuplicateGroup::where('user_id', auth()->id())->where('status', 'pending')->count(),
                'resolved' => DuplicateGroup::where('user_id', auth()->id())->where('status', 'resolved')->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}
