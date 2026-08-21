@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="user" class="h-4 w-4 mr-2"></i> My Profile
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">User Profile</h1>
    <p class="mt-1 text-sm text-slate-500">View your personal account information.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden max-w-3xl">
    <div class="p-6 sm:p-8">
        <div class="flex items-center space-x-6">
            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center shadow-lg text-white text-3xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-slate-500 mt-1 flex items-center">
                    <i data-lucide="mail" class="h-4 w-4 mr-2"></i> {{ $user->email }}
                </p>
            </div>
        </div>

        <div class="mt-8 border-t border-slate-100 pt-8">
            <dl class="divide-y divide-slate-100">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-slate-900">Full name</dt>
                    <dd class="mt-1 text-sm leading-6 text-slate-700 sm:col-span-2 sm:mt-0">{{ $user->name }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-slate-900">Email address</dt>
                    <dd class="mt-1 text-sm leading-6 text-slate-700 sm:col-span-2 sm:mt-0">{{ $user->email }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-slate-900">Account Role</dt>
                    <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 uppercase tracking-wide">
                            {{ $user->role ?? 'User' }}
                        </span>
                    </dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-slate-900">Member Since</dt>
                    <dd class="mt-1 text-sm leading-6 text-slate-700 sm:col-span-2 sm:mt-0">
                        {{ $user->created_at ? $user->created_at->format('F j, Y, g:i a') : 'N/A' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
