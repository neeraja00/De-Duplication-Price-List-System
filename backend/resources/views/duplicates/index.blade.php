@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="git-merge" class="h-4 w-4 mr-2"></i> Review Duplicates
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Duplicate Groups</h1>
    <p class="mt-1 text-sm text-slate-500">System detected identical or similar price lists. Please review and merge them to a canonical master record.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($groups as $group)
        <div class="glass-panel rounded-xl {{ $group->status === 'pending' ? 'border-brand-300 shadow-lg shadow-brand-500/20' : 'border-white/50 shadow-sm opacity-80' }} overflow-hidden flex flex-col transition-all hover:-translate-y-1 hover:shadow-xl stagger-{{ ($loop->index % 4) + 1 }}">
            <div class="p-5 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-flex font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $group->group_code }}</span>
                        <div class="mt-2">
                            @if($group->match_type === 'exact')
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20"><i data-lucide="check" class="w-3 h-3 mr-1"></i> Exact Match</span>
                            @elseif($group->match_type === 'formatting')
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20"><i data-lucide="code" class="w-3 h-3 mr-1"></i> Formatting Diff</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20"><i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Fuzzy Typo</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($group->status === 'pending')
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800"><span class="w-2 h-2 mr-1.5 bg-amber-500 rounded-full animate-pulse"></span> Pending</span>
                        @elseif($group->status === 'resolved')
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Merged</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">Rejected</span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Records</p>
                        <p class="mt-1 text-xl font-semibold text-slate-900">{{ $group->items_count }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Confidence</p>
                        <p class="mt-1 text-xl font-semibold text-emerald-600">{{ $group->confidence_score }}%</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/30 backdrop-blur-sm px-5 py-3 border-t border-white/50">
                <a href="{{ route(auth()->user()->role === 'admin' ? 'admin.duplicates.show' : 'user.duplicates.show', $group->id) }}" class="text-sm font-bold text-brand-600 hover:text-brand-800 flex items-center justify-center w-full transition-colors">
                    Review Group <i data-lucide="arrow-right" class="h-4 w-4 ml-1.5"></i>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="rounded-xl border border-dashed border-slate-300 p-12 text-center">
                <i data-lucide="check-circle-2" class="mx-auto h-12 w-12 text-emerald-400"></i>
                <h3 class="mt-2 text-sm font-semibold text-slate-900">All clear!</h3>
                <p class="mt-1 text-sm text-slate-500">No duplicate groups found. Your price lists are completely clean.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
