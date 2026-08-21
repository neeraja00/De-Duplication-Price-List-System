@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="shield-alert" class="h-4 w-4 mr-2"></i> Audit Logs
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Audit Logs (MongoDB)</h1>
    <p class="mt-1 text-sm text-slate-500">Comprehensive system event logs sourced directly from the MongoDB analytics cluster.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-6 pr-3 text-xs font-semibold text-slate-900 uppercase tracking-wider">Date/Time</th>
                    <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-900 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-900 uppercase tracking-wider">Module/Action</th>
                    <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-900 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-900 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-3 py-3.5 text-xs font-semibold text-slate-900 uppercase tracking-wider text-center">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($logs as $index => $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-500">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <div class="font-medium text-slate-900">{{ $log->user_name ?? 'System' }}</div>
                            <div class="text-slate-500 text-xs">{{ $log->user_email ?? 'N/A' }}</div>
                            <div class="mt-1">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                    {{ ucfirst($log->role ?? 'System') }}
                                </span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <div class="font-medium text-indigo-600 uppercase tracking-wider text-xs mb-1">{{ $log->module ?? 'SYSTEM' }}</div>
                            <span class="inline-flex items-center rounded bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @if(($log->status ?? 'success') === 'success')
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Success</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Failed</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-sm text-slate-700 max-w-xs truncate" title="{{ $log->description }}">
                            {{ $log->description }}
                        </td>
                        <td class="px-3 py-4 text-sm text-center">
                            <button onclick="toggleDetails('details-{{ $log->id ?? $index }}')" class="text-brand-600 hover:text-brand-800 text-xs font-semibold focus:outline-none flex items-center justify-center w-full">
                                <i data-lucide="chevron-down" class="h-4 w-4 mr-1"></i> View
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Expandable Details Row -->
                    <tr id="details-{{ $log->id ?? $index }}" class="hidden bg-slate-50 border-t-0 shadow-inner">
                        <td colspan="6" class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Request Details</h4>
                                    <dl class="grid grid-cols-1 gap-x-4 gap-y-2 text-sm text-slate-600">
                                        <div class="flex">
                                            <dt class="w-24 font-medium text-slate-900">IP Address:</dt>
                                            <dd>{{ $log->ip_address ?? 'N/A' }}</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="w-24 font-medium text-slate-900">User Agent:</dt>
                                            <dd class="truncate" title="{{ $log->user_agent ?? 'N/A' }}">{{ $log->user_agent ?? 'N/A' }}</dd>
                                        </div>
                                        <div class="flex">
                                            <dt class="w-24 font-medium text-slate-900">User ID:</dt>
                                            <dd class="font-mono text-xs mt-0.5">{{ $log->user_id ?? 'N/A' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Metadata</h4>
                                    <div class="bg-slate-900 rounded-md p-3 overflow-x-auto">
                                        <pre class="text-xs text-green-400 font-mono">{{ json_encode($log->metadata ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <i data-lucide="database" class="mx-auto h-12 w-12 text-slate-300 mb-3"></i>
                            <p class="text-sm text-slate-500">No MongoDB audit logs found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<script>
    function toggleDetails(id) {
        const el = document.getElementById(id);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endsection
