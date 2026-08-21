<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::orderBy('created_at', -1)->paginate(50);
        return view('audit_logs.index', compact('logs'));
    }
}
