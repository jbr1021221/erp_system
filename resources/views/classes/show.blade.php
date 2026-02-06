@extends('layouts.app')

@section('title', 'Class Details - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $class->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Code: {{ $class->code }} | Year: {{ $class->academic_year }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('classes.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to List
                </a>
                @can('class-edit')
                <a href="{{ route('classes.edit', $class) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Edit Class
                </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Class Info & Sections -->
            <div class="lg:col-span-2 space-y-6">
                 <!-- Students List -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Enrolled Students</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $class->students->count() }} / {{ $class->capacity }}
                        </span>
                    </div>
                    <ul role="list" class="divide-y divide-gray-200">
                        @forelse($class->students as $student)
                        <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($student->photo)
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url($student->photo) }}" alt="">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                            {{ substr($student->first_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $student->full_name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $student->student_id }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-sm font-semibold text-gray-900">
                                    {{ $student->section->name ?? 'No Section' }}
                                </div>
                                <div>
                                    <a href="{{ route('students.show', $student) }}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                        View
                                    </a>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-6 py-8 text-center text-gray-500">
                            No students enrolled in this class yet.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Right Column: Stats & Teachers -->
            <div class="space-y-6">
                <!-- Class Teacher -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Class Teacher</h3>
                    </div>
                    <div class="p-6">
                        @if($class->classTeacher)
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                         <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $class->classTeacher->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $class->classTeacher->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No class teacher assigned.</p>
                        @endif
                    </div>
                </div>

                <!-- Sections -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                         <h3 class="text-lg font-medium leading-6 text-gray-900">Sections</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                             @forelse($class->sections as $section)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $section->name }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-500 italic">No sections created for this class.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                 <!-- Quick Stats -->
                <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100">
                    <h4 class="text-sm font-semibold text-indigo-900 uppercase tracking-wider mb-4">Class Overview</h4>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-medium text-indigo-500">Capacity</dt>
                            <dd class="mt-1 text-2xl font-bold text-indigo-700">{{ $class->capacity }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-indigo-500">Status</dt>
                            <dd class="mt-1 text-lg font-bold {{ $class->is_active ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $class->is_active ? 'Active' : 'Inactive' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
