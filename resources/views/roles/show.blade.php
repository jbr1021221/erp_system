@extends('layouts.app')

@section('title', 'Role Details - ERP System')

@section('subnav')
  <a href="{{ route('users.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('users.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Users</a>
  <a href="{{ route('roles.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('roles.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Roles & Perms</a>
  <a href="{{ route('reports.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('reports.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Reports</a>
@endsection


@section('content')

    
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-slate-800">
                    Role: {{ $role->name }}
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-slate-500">
                        <span class="font-medium text-slate-800 mr-1">Created:</span> {{ $role->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('roles.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
                @can('role-edit')
                <a href="{{ route('roles.edit', $role->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Edit Role
                </a>
                @endcan
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-100">
                <h3 class="text-lg font-medium text-slate-800">Assigned Permissions</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @forelse($rolePermissions as $permission)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <span class="text-slate-500 italic">No permissions assigned to this role.</span>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Users with this role -->
         <div class="mt-8 bg-white rounded-lg border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-100">
                <h3 class="text-lg font-medium text-slate-800">Users with this Role</h3>
            </div>
            <div class="p-6">
                <ul class="divide-y divide-gray-200">
                    @php
                        $users = \App\Models\User::role($role->name)->get();
                    @endphp
                    @forelse($users as $user)
                         <li class="py-3 flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-800 font-bold text-xs uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500 italic">No users currently assigned to this role.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endsection
