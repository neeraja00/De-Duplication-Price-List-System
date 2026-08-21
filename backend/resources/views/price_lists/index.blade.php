@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="database" class="h-4 w-4 mr-2"></i> Price Lists
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Price List Records</h1>
    <p class="mt-1 text-sm text-slate-500">All structured business records stored in MongoDB.</p>
</div>

<div class="glass-panel rounded-xl overflow-hidden shadow-sm stagger-1">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200/50">
            <thead class="bg-white/50 backdrop-blur-sm">
                <tr>
                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">PL Number (Raw)</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Normalized</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Item Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Vendor</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold text-slate-900 uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/50">
                @forelse($records as $record)
                    <tr class="table-hover-row hover:bg-white/60 transition-colors">
                        <td class="whitespace-nowrap py-3 pl-6 pr-3 text-xs text-slate-500">#{{ $record->id }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm font-medium text-slate-900 font-mono">{{ $record->pl_number_original }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-xs text-slate-500 font-mono">{{ $record->pl_number_normalized }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-700">{{ $record->item_name }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-700">{{ $record->vendor_name }}</td>
                        <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-900 font-semibold text-right">{{ $record->currency }} {{ number_format($record->price, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-3 text-center">
                            @if($record->status === 'active')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Active Master</span>
                            @elseif($record->status === 'merged')
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/20">Merged</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Duplicate</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-sm text-slate-500">No records found. Upload a file to begin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $records->links() }}
        </div>
    @endif
</div>
@endsection
