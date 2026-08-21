@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="bar-chart-2" class="h-4 w-4 mr-2"></i> Reports
@endsection

@section('content')
<div class="mb-8">
    @if(auth()->user()->role === 'admin')
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">System Reports</h1>
        <p class="mt-1 text-sm text-slate-500">View global system aggregates and data health metrics.</p>
    @else
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Reports</h1>
        <p class="mt-1 text-sm text-slate-500">View your data health and export your fully cleaned, deduplicated canonical dataset.</p>
    @endif
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="glass-panel overflow-hidden rounded-xl relative overflow-hidden stagger-1 hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 w-2 h-full bg-emerald-500"></div>
        <div class="p-5">
            <dt class="text-sm font-medium text-slate-500 truncate">Active Clean Records</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ $stats['active_records'] }}</dd>
        </div>
    </div>
    <div class="glass-panel overflow-hidden rounded-xl relative overflow-hidden stagger-2 hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 w-2 h-full bg-blue-500"></div>
        <div class="p-5">
            <dt class="text-sm font-medium text-slate-500 truncate">Merged Records</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ $stats['merged_records'] }}</dd>
        </div>
    </div>
    <div class="glass-panel overflow-hidden rounded-xl relative overflow-hidden stagger-3 hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 w-2 h-full bg-indigo-500"></div>
        <div class="p-5">
            <dt class="text-sm font-medium text-slate-500 truncate">Total Groups Detected</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ $stats['total_groups'] }}</dd>
        </div>
    </div>
    <div class="glass-panel overflow-hidden rounded-xl relative overflow-hidden stagger-4 hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 w-2 h-full bg-slate-400"></div>
        <div class="p-5">
            <dt class="text-sm font-medium text-slate-500 truncate">Resolved Groups</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ $stats['resolved_groups'] }}</dd>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
<div class="mb-4 flex items-center justify-between mt-8">
    <h2 class="text-lg font-semibold text-slate-900">User Activity Breakdown</h2>
    <p class="text-sm text-slate-500">Detailed deduplication metrics per user.</p>
</div>

<div class="glass-panel rounded-xl overflow-hidden mb-8 stagger-2">
    <table class="min-w-full divide-y divide-slate-200/50">
        <thead class="bg-white/50 backdrop-blur-sm">
            <tr>
                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">User</th>
                <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">Uploads</th>
                <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">Records</th>
                <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">Duplicates Detected</th>
                <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">Duplicates Resolved</th>
                <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">Merges Performed</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200/50">
            @forelse($userBreakdown ?? [] as $ub)
                <tr class="table-hover-row hover:bg-white/60 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="font-medium text-slate-900">{{ $ub['name'] }}</div>
                        <div class="text-slate-500 text-sm">{{ $ub['email'] }}</div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 text-center font-medium">{{ number_format($ub['uploads']) }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 text-center">{{ number_format($ub['records']) }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-amber-600 text-center font-bold">{{ number_format($ub['detected']) }}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-emerald-600 text-center font-bold">{{ number_format($ub['resolved']) }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-indigo-600 text-center font-bold">{{ number_format($ub['merges']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-sm text-slate-500">No user activity found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif

@if(auth()->user()->role === 'user')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="glass-panel rounded-xl overflow-hidden flex flex-col h-full stagger-1 hover:-translate-y-1 transition-transform duration-300">
        <div class="p-6 flex-1">
            <div class="flex items-center mb-4">
                <div class="bg-brand-50 rounded-lg p-3 mr-4">
                    <i data-lucide="download-cloud" class="h-6 w-6 text-brand-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Cleaned Master Price List</h3>
                    <p class="text-sm text-slate-500">The final, deduplicated dataset containing only canonical and unique records.</p>
                </div>
            </div>
            <ul class="mt-4 space-y-2 text-sm text-slate-600 list-disc list-inside">
                <li>Excludes all merged duplicates</li>
                <li>Includes normalized vendor names</li>
                <li>CSV format ready for ERP import</li>
            </ul>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <a href="{{ route('user.reports.download', 'cleaned') }}" class="w-full flex justify-center items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                <i data-lucide="file-spreadsheet" class="h-4 w-4 mr-2 text-slate-400"></i> Download CSV Export
            </a>
        </div>
    </div>

    <div class="glass-panel rounded-xl overflow-hidden flex flex-col h-full hover:-translate-y-1 transition-transform duration-300">
        <div class="p-6 flex-1">
            <div class="flex items-center mb-4">
                <div class="bg-orange-50 rounded-lg p-3 mr-4">
                    <i data-lucide="copy" class="h-6 w-6 text-orange-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Duplicate Records</h3>
                    <p class="text-sm text-slate-500">All records currently flagged as duplicates.</p>
                </div>
            </div>
            <ul class="mt-4 space-y-2 text-sm text-slate-600 list-disc list-inside">
                <li>Includes exact and fuzzy matches</li>
                <li>Pending review status</li>
            </ul>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <a href="{{ route('user.reports.download', 'duplicates') }}" class="w-full flex justify-center items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                <i data-lucide="file-text" class="h-4 w-4 mr-2 text-slate-400"></i> Download CSV Export
            </a>
        </div>
    </div>

    <div class="glass-panel rounded-xl overflow-hidden flex flex-col h-full hover:-translate-y-1 transition-transform duration-300">
        <div class="p-6 flex-1">
            <div class="flex items-center mb-4">
                <div class="bg-red-50 rounded-lg p-3 mr-4">
                    <i data-lucide="x-circle" class="h-6 w-6 text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Rejected Duplicates</h3>
                    <p class="text-sm text-slate-500">Records that were marked as non-duplicates.</p>
                </div>
            </div>
            <ul class="mt-4 space-y-2 text-sm text-slate-600 list-disc list-inside">
                <li>Ignored during merge review</li>
                <li>Will not be flagged again</li>
            </ul>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <a href="{{ route('user.reports.download', 'rejected') }}" class="w-full flex justify-center items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                <i data-lucide="file-minus" class="h-4 w-4 mr-2 text-slate-400"></i> Download CSV Export
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
        <div class="p-6 flex-1">
            <div class="flex items-center mb-4">
                <div class="bg-indigo-50 rounded-lg p-3 mr-4">
                    <i data-lucide="history" class="h-6 w-6 text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Merge History Logs</h3>
                    <p class="text-sm text-slate-500">A complete audit trail of all merges.</p>
                </div>
            </div>
            <ul class="mt-4 space-y-2 text-sm text-slate-600 list-disc list-inside">
                <li>Shows who merged which records</li>
                <li>Includes timestamps and notes</li>
            </ul>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            <a href="{{ route('user.reports.download', 'merges') }}" class="w-full flex justify-center items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                <i data-lucide="file-clock" class="h-4 w-4 mr-2 text-slate-400"></i> Download CSV Export
            </a>
        </div>
    </div>
</div>
@endif
@endsection
