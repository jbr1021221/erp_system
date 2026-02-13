@extends('layouts.app')

@section('title', 'Roles - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate">
                    Role Management
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Define roles and assign permissions to control access.
                </p>
            </div>
            @can('role-create')
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Role
                </a>
            </div>
            @endcan
        </div>

        <!-- Role Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($roles as $role)
            <div class="rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold" style="color: rgb(var(--text-primary));">{{ $role->name }}</h3>
                            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">
                                {{ $role->permissions->count() }} active permissions
                            </p>
                        </div>
                        <div class="flex space-x-2">
                             @can('role-edit')
                                @if($role->name != 'Super Admin')
                                <a href="{{ route('roles.edit', $role->id) }}" class="transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(var(--text-primary))';" onmouseout="this.style.color='rgb(var(--text-tertiary));';">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @endif
                            @endcan
                            
                            @can('role-delete')
                                @if($role->name != 'Super Admin')
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(var(--error))';" onmouseout="this.style.color='rgb(var(--text-tertiary));';">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3" style="background-color: rgb(var(--bg-secondary)); border-top: 1px solid rgb(var(--border-primary));">
                    <div class="flex flex-wrap gap-1">
                        @foreach($role->permissions->take(5) as $permission)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" style="background-color: rgb(var(--bg-tertiary)); color: rgb(var(--text-secondary));">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                        @if($role->permissions->count() > 5)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" style="background-color: rgb(var(--bg-tertiary)); color: rgb(var(--text-secondary));">
                                +{{ $role->permissions->count() - 5 }} more
                            </span>
                        @endif
                    </div>
                    <div class="mt-3">
                         <a href="{{ route('roles.show', $role->id) }}" class="text-xs font-medium transition-colors" style="color: rgb(var(--text-secondary));" onmouseover="this.style.color='rgb(var(--text-primary))';" onmouseout="this.style.color='rgb(var(--text-secondary))';" >View Full Details &rarr;</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $roles->render() }}
        </div>
    </div>
</div>
@endsection
