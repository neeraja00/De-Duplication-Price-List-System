<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('role', 'admin')->first();
auth()->login($user);

// Simulate dashboard
$controller = app(\App\Http\Controllers\DashboardController::class);
$view = $controller->index();
echo "Dashboard rendered successfully.\n";

// Simulate reports
$controller = app(\App\Http\Controllers\ReportController::class);
$view = $controller->index();
echo "Reports rendered successfully.\n";
