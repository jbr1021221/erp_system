@extends('layouts.app')

@section('title', 'Create Role - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-slate-800">
                    Create New Role
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Define a new role and assign permissions.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Role Name <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g., Accountant" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md md:w-1/2">
                    </div>
                     @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-800">Permissions</h3>
                    <div class="flex space-x-2">
                        <button type="button" onclick="selectAll()" class="text-xs text-slate-800 hover:text-slate-800">Select All</button>
                        <span class="text-slate-300">|</span>
                        <button type="button" onclick="deselectAll()" class="text-xs text-slate-500 hover:text-slate-800">Deselect All</button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($permissionGroups as $group => $permissions)
                        <div class="border border-slate-200 rounded-xl p-6 bg-slate-100 bg-opacity-50">
                            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-3">{{ ucfirst($group) }} Management</h4>
                            <div class="space-y-2">
                                @foreach($permissions as $permission)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="permission_{{ $permission->id }}" name="permission[]" type="checkbox" value="{{ $permission->id }}" class="permission-checkbox focus:ring-slate-500 h-5 w-5 text-slate-800 border-slate-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="permission_{{ $permission->id }}" class="font-medium text-slate-700 select-none cursor-pointer">
                                            {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-100 border-t border-slate-200 flex justify-end">
                     <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Create Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function selectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(c => c.checked = true);
    }
    function deselectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(c => c.checked = false);
    }
</script>
@endsection
