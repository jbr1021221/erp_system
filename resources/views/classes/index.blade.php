@extends('layouts.app')

@section('title', 'Classes - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Academic Classes
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage classes, sections, and assign class teachers.
                </p>
            </div>
            @can('class-create')
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('classes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Class
                </a>
            </div>
            @endcan
        </div>

        <!-- Class List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classes as $class)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $class->name }}</h3>
                                @if(!$class->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Code: {{ $class->code }}</p>
                        </div>
                        <div class="dropdown relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                </svg>
                            </button>
                            <div x-show="open" class="dropdown-menu absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
                                @can('class-edit')
                                <a href="{{ route('classes.edit', $class) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Class</a>
                                @endcan
                                @can('class-delete')
                                <form action="{{ route('classes.destroy', $class) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" onclick="return confirm('Are you sure?')">Delete Class</button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $class->classTeacher->name ?? 'No Class Teacher' }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                             <span class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 flex items-center justify-center font-bold text-xs border border-gray-300 rounded-full">S</span>
                             {{ $class->sections->count() }} Sections
                        </div>
                    </div>
                    
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <div class="flex flex-wrap gap-2">
                            @forelse($class->sections as $section)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $section->name }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-400 italic">No sections added</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                    <div class="text-xs text-gray-500">
                        Academic Year: <span class="font-medium text-gray-900">{{ $class->academic_year }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No classes</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new class.</p>
                    <div class="mt-6">
                        @can('class-create')
                        <a href="{{ route('classes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Create Class
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
             {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection
