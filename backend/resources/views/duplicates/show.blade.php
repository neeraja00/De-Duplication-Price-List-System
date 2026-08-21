@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.duplicates.index' : 'user.duplicates.index') }}" class="hover:text-brand-600 flex items-center">
        <i data-lucide="git-merge" class="h-4 w-4 mr-2"></i> Review Duplicates
    </a>
    <i data-lucide="chevron-right" class="h-4 w-4 mx-2 text-slate-300"></i>
    <span class="text-slate-900">Group {{ $group->group_code }}</span>
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div>
        <div class="flex items-center">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mr-3">Compare Records</h1>
            <span class="inline-flex font-mono text-sm font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded border border-slate-200">{{ $group->group_code }}</span>
        </div>
        <p class="mt-1 text-sm text-slate-500">Select the master (canonical) record. The remaining records will be merged into it.</p>
    </div>
    
    <div class="mt-4 sm:mt-0 flex gap-3">
        @if($group->status === 'pending')
            <form action="{{ route(auth()->user()->role === 'admin' ? 'admin.duplicates.reject' : 'user.duplicates.reject', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this group? They will be marked as distinct records.');">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition-colors">
                    <i data-lucide="x" class="h-4 w-4 mr-1.5"></i> Reject Group
                </button>
            </form>
            <button type="submit" form="mergeForm" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                <i data-lucide="check" class="h-4 w-4 mr-1.5"></i> Merge Selected
            </button>
        @else
            <span class="inline-flex items-center rounded-md bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
                <i data-lucide="info" class="h-4 w-4 mr-1.5"></i> This group has been {{ $group->status }}
            </span>
        @endif
    </div>
</div>

<form action="{{ route(auth()->user()->role === 'admin' ? 'admin.duplicates.merge' : 'user.duplicates.merge', $group->id) }}" method="POST" id="mergeForm">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($group->items as $item)
            @php $record = $item->priceList; @endphp
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm relative overflow-hidden flex flex-col focus-within:ring-2 focus-within:ring-brand-500 transition-all hover:shadow-md">
                <div class="border-b border-slate-100 px-5 py-4 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="record_{{ $record->id }}" name="canonical_id" type="radio" value="{{ $record->id }}" class="h-5 w-5 border-slate-300 text-brand-600 focus:ring-brand-600 cursor-pointer" {{ $loop->first ? 'checked' : '' }} {{ $group->status !== 'pending' ? 'disabled' : '' }}>
                        <label for="record_{{ $record->id }}" class="ml-3 block text-sm font-medium leading-6 text-slate-900 cursor-pointer">
                            Make Master
                        </label>
                    </div>
                    <span class="text-xs font-mono text-slate-400">ID: #{{ $record->id }}</span>
                </div>
                
                <div class="p-6 flex-1 space-y-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Original PL Number</p>
                        <div class="bg-slate-100 rounded border border-slate-200 p-2">
                            <span class="font-mono text-lg font-bold text-slate-800">{{ $record->pl_number_original }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Item Name</p>
                            <p class="text-sm font-medium text-slate-900">{{ $record->item_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Vendor</p>
                            <p class="text-sm font-medium text-slate-900">{{ $record->vendor_name ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Price</p>
                            <p class="text-lg font-bold text-slate-900">{{ $record->currency }} {{ $record->price ?? '0.00' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Effective Date</p>
                            <p class="text-sm font-medium text-slate-900">{{ $record->effective_date ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-slate-50 border-t border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Normalized Internal String</p>
                    <p class="font-mono text-xs text-slate-500 truncate">{{ $record->pl_number_normalized }}</p>
                </div>
            </div>
        @endforeach
    </div>
</form>
@endsection
