@extends('layouts.app')

@section('title', 'Create Role - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Create New Role
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Define a new role and assign permissions.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('roles.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Role Name <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g., Accountant" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md md:w-1/2">
                    </div>
                     @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Permissions</h3>
                    <div class="flex space-x-2">
                        <button type="button" onclick="selectAll()" class="text-xs text-indigo-600 hover:text-indigo-900">Select All</button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="deselectAll()" class="text-xs text-gray-500 hover:text-gray-800">Deselect All</button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($permissionGroups as $group => $permissions)
                        <div class="border border-gray-100 rounded-lg p-4 bg-gray-50 bg-opacity-50">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">{{ ucfirst($group) }} Management</h4>
                            <div class="space-y-2">
                                @foreach($permissions as $permission)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="permission_{{ $permission->id }}" name="permission[]" type="checkbox" value="{{ $permission->id }}" class="permission-checkbox focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="permission_{{ $permission->id }}" class="font-medium text-gray-700 select-none cursor-pointer">
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
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                     <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
