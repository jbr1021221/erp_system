@extends('layouts.app')

@section('title', 'Role Details - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Role: {{ $role->name }}
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span class="font-medium text-gray-900 mr-1">Created:</span> {{ $role->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('roles.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to List
                </a>
                @can('role-edit')
                <a href="{{ route('roles.edit', $role->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Role
                </a>
                @endcan
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Assigned Permissions</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @forelse($rolePermissions as $permission)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <span class="text-gray-500 italic">No permissions assigned to this role.</span>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Users with this role -->
         <div class="mt-8 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Users with this Role</h3>
            </div>
            <div class="p-6">
                <ul class="divide-y divide-gray-200">
                    @php
                        $users = \App\Models\User::role($role->name)->get();
                    @endphp
                    @forelse($users as $user)
                         <li class="py-3 flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500 italic">No users currently assigned to this role.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
