<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\MergeLogController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/user/dashboard');
    }
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    // User Auth
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Admin Auth
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // User Routes
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/import', [UploadController::class, 'create'])->name('upload.create');
        Route::post('/import', [UploadController::class, 'store'])->name('upload.store');
        
        Route::get('/price-lists', [PriceListController::class, 'index'])->name('price_lists.index');
        
        Route::get('/duplicates', [DuplicateController::class, 'index'])->name('duplicates.index');
        Route::get('/duplicates/{id}', [DuplicateController::class, 'show'])->name('duplicates.show');
        Route::post('/duplicates/{id}/merge', [DuplicateController::class, 'merge'])->name('duplicates.merge');
        Route::post('/duplicates/{id}/reject', [DuplicateController::class, 'reject'])->name('duplicates.reject');
        
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/download/{type}', [ReportController::class, 'download'])->name('reports.download');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    });
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/download/{type}', [ReportController::class, 'download'])->name('reports.download');
        
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
        
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    });
});
