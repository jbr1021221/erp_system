@extends('layouts.app')

@section('title', 'Create User - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-slate-800">
                    Create New User
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Add a new user to the system and assign a role.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Photo -->
                    <div class="sm:col-span-6">
                        <label for="photo" class="block text-sm font-medium text-slate-700">Profile Photo</label>
                        <div class="mt-1 flex items-center">
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-slate-200">
                                <svg class="h-full w-full text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <div class="ml-4">
                                <input type="file" name="photo" id="photo" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200">
                            </div>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-slate-700">Full Name <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-slate-700">Email Address <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                         @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm font-medium text-slate-700">Phone Number</label>
                        <div class="mt-1">
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                    </div>
                    
                    <!-- Role -->
                    <div class="sm:col-span-3">
                        <label for="role" class="block text-sm font-medium text-slate-700">Role <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select id="role" name="role" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                         @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div class="sm:col-span-3">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="password" name="password" id="password" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                         @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirm Password -->
                     <div class="sm:col-span-3">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-6">
                        <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
                        <div class="mt-1">
                            <select id="status" name="status" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-200 flex justify-end">
                    <a href="{{ route('users.index') }}" class="bg-slate-50 py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
