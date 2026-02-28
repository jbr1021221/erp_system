@extends('layouts.app')

@section('title', 'Create Class - ERP System')

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('teachers.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('teachers.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Teachers</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800'}}">Classes</a>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 pb-5 border-b border-slate-200 dark:border-slate-700">
            <div>
                <h1 class="text-xl font-semibold text-slate-800 dark:text-slate-100">Create New Class</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Add a new academic class to the system.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('classes.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    Back to List
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">

            {{-- Section header --}}
            <div class="px-6 pt-6 pb-0">
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-200 dark:border-slate-700">
                    <svg class="text-emerald-500 size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h2 class="text-xs font-semibold tracking-widest text-slate-400 uppercase">Class Details</h2>
                </div>
            </div>

            <form action="{{ route('classes.store') }}" method="POST" class="px-6 pb-6">
                @csrf

                <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6">

                    {{-- Class Name --}}
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Class Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g., Grade 10" required
                               class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        @error('name') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Class Code --}}
                    <div class="sm:col-span-2">
                        <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Class Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="e.g., G10" required
                               class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        @error('code') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Academic Year --}}
                    <div class="sm:col-span-3">
                        <label for="academic_year" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', date('Y')) }}" required
                               class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        @error('academic_year') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Capacity --}}
                    <div class="sm:col-span-3">
                        <label for="capacity" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Capacity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 40) }}" required
                               class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                        @error('capacity') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Class Teacher --}}
                    <div class="sm:col-span-6">
                        <label for="class_teacher_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Class Teacher
                        </label>
                        <select id="class_teacher_id" name="class_teacher_id"
                                class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="">Select a Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('class_teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="block w-full rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-vertical">{{ old('description') }}</textarea>
                    </div>

                    {{-- Active Status --}}
                    <div class="sm:col-span-6">
                        <div class="flex items-start gap-3">
                            <div class="flex items-center h-5 mt-0.5">
                                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 bg-white dark:bg-slate-800">
                            </div>
                            <div>
                                <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300">Active Status</label>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Uncheck to mark this class as inactive/archived.</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="pt-5 mt-2 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                    <a href="{{ route('classes.index') }}"
                       class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
