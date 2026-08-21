@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="settings" class="h-4 w-4 mr-2"></i> Settings
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Deduplication Rules</h1>
    <p class="mt-1 text-sm text-slate-500">Configure how the system identifies and scores duplicate records.</p>
</div>

<form action="{{ route('admin.settings.store') }}" method="POST" class="max-w-3xl">
    @csrf
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base leading-6 font-semibold text-slate-900">Normalization String Cleanup</h3>
            <p class="mt-1 text-sm text-slate-500">These settings apply when stripping characters to find Exact and Formatting matches.</p>
        </div>
        <div class="p-6 space-y-6">
            <!-- Toggle 1 -->
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-slate-900">Ignore Spaces</h4>
                    <p class="text-sm text-slate-500">Treat "PL 123" and "PL123" as identical.</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="ignore_spaces" id="ignore_spaces" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600" {{ $settings->ignore_spaces ? 'checked' : '' }}>
                </div>
            </div>
            <hr class="border-slate-100">
            <!-- Toggle 2 -->
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-slate-900">Ignore Hyphens</h4>
                    <p class="text-sm text-slate-500">Treat "PL-123" and "PL123" as identical.</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="ignore_hyphens" id="ignore_hyphens" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600" {{ $settings->ignore_hyphens ? 'checked' : '' }}>
                </div>
            </div>
            <hr class="border-slate-100">
            <!-- Toggle 3 -->
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-slate-900">Ignore Special Characters</h4>
                    <p class="text-sm text-slate-500">Strip out #, @, %, etc.</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="ignore_special_characters" id="ignore_special_characters" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600" {{ $settings->ignore_special_characters ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base leading-6 font-semibold text-slate-900">Advanced Matching</h3>
            <p class="mt-1 text-sm text-slate-500">Configure Levenshtein distance rules for Fuzzy/Typo matching.</p>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="fuzzy_match_threshold" class="block text-sm font-medium leading-6 text-slate-900">Fuzzy Match Threshold (%)</label>
                    <div class="mt-2">
                        <input type="number" name="fuzzy_match_threshold" id="fuzzy_match_threshold" min="50" max="99" value="{{ $settings->fuzzy_match_threshold }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm sm:leading-6 px-3">
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Higher threshold = stricter matching. Default is 85%.</p>
                </div>
            </div>
            <hr class="border-slate-100">
            <!-- Toggle 4 -->
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-slate-900">Ignore Leading Zeros</h4>
                    <p class="text-sm text-slate-500">Treat "000123" and "123" as identical.</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="ignore_leading_zeros" id="ignore_leading_zeros" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600" {{ $settings->ignore_leading_zeros ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center rounded-md bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 transition-colors">
            <i data-lucide="save" class="h-4 w-4 mr-2"></i> Save Configuration
        </button>
    </div>
</form>
@endsection
