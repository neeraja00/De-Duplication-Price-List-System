@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="home" class="h-4 w-4 mr-2"></i> Dashboard
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Overview</h1>
    <p class="mt-1 text-sm text-slate-500">A high-level view of your data deduplication system in MongoDB.</p>
</div>

@if((auth()->user()->role ?? 'user') === 'admin')
<!-- Admin Metrics Grid -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
    <!-- Active Users -->
    <div class="glass-panel overflow-hidden rounded-2xl hover:-translate-y-1 transition-transform duration-300 relative stagger-1">
        <div class="absolute top-3 right-3 w-3 h-3 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></div>
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl p-3 shadow-lg shadow-teal-500/30">
                    <i data-lucide="activity" class="h-6 w-6 text-white"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 whitespace-normal leading-tight">Active Now</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-slate-900">{{ $stats['active_users'] ?? 0 }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Users -->
    <div class="glass-panel overflow-hidden rounded-2xl hover:-translate-y-1 transition-transform duration-300 stagger-2">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl p-3 shadow-lg shadow-indigo-500/30">
                    <i data-lucide="users" class="h-6 w-6 text-white"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 whitespace-normal leading-tight">Total Users</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-slate-900">{{ $stats['total_users'] ?? 0 }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@else
<!-- User Metrics Grid -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
@endif
    <!-- Total Uploads -->
    <div class="glass-panel overflow-hidden rounded-2xl hover:-translate-y-1 transition-transform duration-300 stagger-1">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-blue-400 to-brand-600 rounded-xl p-3 shadow-lg shadow-blue-500/30">
                    <i data-lucide="cloud-upload" class="h-6 w-6 text-white"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 whitespace-normal leading-tight">Total Files Uploaded</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-slate-900">{{ $stats['total_uploads'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Records -->
    <div class="bg-white overflow-hidden rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-1 transition-transform duration-300">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl p-3 shadow-lg shadow-emerald-500/30">
                    <i data-lucide="database" class="h-6 w-6 text-white"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 whitespace-normal leading-tight">Total Price Lists</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-slate-900">{{ $stats['total_records'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Review -->
    <div class="glass-panel overflow-hidden rounded-2xl hover:-translate-y-1 transition-transform duration-300 relative stagger-3">
        @if($stats['pending_review'] > 0)
            <div class="absolute top-0 right-0 w-2 h-full bg-gradient-to-b from-amber-300 to-amber-500"></div>
        @endif
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl p-3 shadow-lg shadow-amber-500/30">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-white"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 whitespace-normal leading-tight">Pending Reviews</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-slate-900">{{ $stats['pending_review'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolved -->
    <div class="glass-panel overflow-hidden rounded-2xl hover:-translate-y-1 transition-transform duration-300 stagger-4">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-purple-400 to-indigo-600 rounded-xl p-3 shadow-lg shadow-indigo-500/30">
                    <i data-lucide="check-square" class="h-6 w-6 text-white"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-slate-500 whitespace-normal leading-tight">Resolved Duplicates</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-slate-900">{{ $stats['resolved'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Architecture Info -->
    <div class="glass-panel rounded-xl flex flex-col stagger-3">
        <div class="px-6 py-5 border-b border-white/50">
            <h3 class="text-base leading-6 font-semibold text-slate-900">System Architecture</h3>
            <p class="mt-1 text-sm text-slate-500">Live Single-Database Infrastructure</p>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-blue-50 rounded-md p-2 mt-1 border border-blue-100">
                    <i data-lucide="database" class="h-5 w-5 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-bold text-slate-900">MongoDB (Active)</h4>
                    <p class="text-sm text-slate-500 mt-1">Handling structured business records, users, and core settings. Ensures transactional integrity for merges.</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-sm">
            @if((auth()->user()->role ?? 'user') === 'admin')
            <a href="{{ route('admin.settings.index') }}" class="font-medium text-brand-600 hover:text-brand-500 flex items-center">
                Configure Deduplication Settings <i data-lucide="arrow-right" class="h-4 w-4 ml-1"></i>
            </a>
            @else
            <p class="font-medium text-slate-500 flex items-center">
                System is running optimally.
            </p>
            @endif
        </div>
    </div>
    @if((auth()->user()->role ?? 'user') === 'admin')
    <!-- Recent System Activity -->
    <div class="glass-panel rounded-xl flex flex-col stagger-4">
        <div class="px-6 py-5 border-b border-white/50 flex justify-between items-center">
            <div>
                <h3 class="text-base leading-6 font-semibold text-slate-900">Recent Security & Audit Logs</h3>
                <p class="mt-1 text-sm text-slate-500">Real-time system activity monitoring.</p>
            </div>
            <a href="{{ route('admin.audit_logs.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-500">View All</a>
        </div>
        <div class="p-0 flex-1 overflow-y-auto">
            <ul class="divide-y divide-slate-100">
                @foreach($recentLogs ?? [] as $log)
                <li class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex space-x-3">
                        @if($log->status === 'success')
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center"><i data-lucide="check" class="h-4 w-4 text-emerald-600"></i></div>
                        @else
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-100 flex items-center justify-center"><i data-lucide="x" class="h-4 w-4 text-red-600"></i></div>
                        @endif
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-slate-900">{{ $log->action }}</h3>
                                <p class="text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-sm text-slate-500">{{ $log->description }}</p>
                            <p class="text-xs font-medium text-slate-400">By: {{ $log->user_name ?? 'System' }} ({{ $log->user_email ?? 'N/A' }})</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
@endsection
