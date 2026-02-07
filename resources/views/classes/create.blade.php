@extends('layouts.app')

@section('title', 'Create Class - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate">
                    Create New Class
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Add a new academic class to the system.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('classes.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-slate-50 shadow-sm rounded-xl border border-slate-200 overflow-hidden">
            <form action="{{ route('classes.store') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-slate-700">Class Name <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g., Grade 10" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-slate-700">Class Code <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="e.g., G10" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                        @error('code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="academic_year" class="block text-sm font-medium text-slate-700">Academic Year <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', date('Y')) }}" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                        @error('academic_year') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="capacity" class="block text-sm font-medium text-slate-700">Capacity <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 40) }}" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                         @error('capacity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="class_teacher_id" class="block text-sm font-medium text-slate-700">Class Teacher</label>
                        <div class="mt-1">
                            <select id="class_teacher_id" name="class_teacher_id" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                                <option value="">Select a Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('class_teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border border-slate-300 rounded-md">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="sm:col-span-6">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="focus:ring-slate-500 h-5 w-5 text-slate-800 border-slate-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-slate-700">Active Status</label>
                                <p class="text-slate-500">Uncheck to mark this class as inactive/archived.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-200 flex justify-end">
                    <a href="{{ route('classes.index') }}" class="bg-slate-50 py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
