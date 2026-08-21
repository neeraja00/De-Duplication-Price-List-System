@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="history" class="h-4 w-4 mr-2"></i> Merge History
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Merge History</h1>
    <p class="mt-1 text-sm text-slate-500">A permanent log of all duplicate merges performed in the system.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Group</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Master Record ID</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Merged Record IDs</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Merged By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-500">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-slate-900 font-mono">{{ $log->group->group_code ?? 'Deleted Group' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-emerald-600 font-mono">#{{ $log->canonical_price_list_id }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500 font-mono">
                            @php
                                $mergedIds = $log->merged_price_list_ids;
                            @endphp
                            @if(is_array($mergedIds))
                                {{ implode(', #', $mergedIds) }}
                            @else
                                {{ $log->merged_price_list_ids }}
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-700">
                            <div class="flex items-center">
                                <i data-lucide="user" class="h-4 w-4 mr-1 text-slate-400"></i>
                                {{ $log->mergedBy->name ?? 'System' }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-sm text-slate-500">No merge history found.</td>
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
@endsection
