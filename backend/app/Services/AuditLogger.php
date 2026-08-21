<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Log an action to the MongoDB audit_logs collection.
     *
     * @param string $action
     * @param string $module
     * @param string $status
     * @param string $description
     * @param array $metadata
     * @return void
     */
    public static function log($action, $module, $status, $description, $metadata = [])
    {
        $request = request();
        $user = Auth::user();

        AuditLog::create([
            'user_id' => $user ? (string) $user->id : null,
            'user_name' => $user ? $user->name : 'System/Guest',
            'user_email' => $user ? $user->email : ($metadata['attempted_email'] ?? 'Unknown'),
            'role' => $user ? $user->role : 'guest',
            'action' => $action,
            'module' => $module,
            'status' => $status,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
