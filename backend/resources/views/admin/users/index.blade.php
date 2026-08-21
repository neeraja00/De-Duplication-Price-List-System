@extends('layouts.app')

@section('breadcrumbs')
    <i data-lucide="users" class="h-4 w-4 mr-2"></i> Users Management
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Registered Users</h1>
    <p class="mt-1 text-sm text-slate-500">View and manage all registered users in the platform.</p>
</div>

<div class="glass-panel rounded-xl overflow-hidden shadow-sm stagger-1 mb-8">
    <table class="min-w-full divide-y divide-slate-200/50">
        <thead class="bg-white/50 backdrop-blur-sm">
            <tr>
                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">User</th>
                <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Role</th>
                <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wider">Registered</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200/50">
            @forelse($users as $user)
                <tr class="table-hover-row hover:bg-white/60 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-6 pr-3">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center shadow-md">
                                <span class="text-sm font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                <div class="text-slate-500 text-sm">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20">Admin</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">User</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $user->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-sm text-slate-500">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
