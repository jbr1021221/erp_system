@extends('layouts.app')

@section('title', 'Class Details - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate">
                    {{ $class->name }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Code: {{ $class->code }} | Year: {{ $class->academic_year }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('classes.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
                @can('class-edit')
                <a href="{{ route('classes.edit', $class) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Edit Class
                </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Class Info & Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Fee Structure -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-900">Fee Structure</h3>
                        @can('fee-structure-create')
                        <a href="{{ route('fee-structures.create', ['class_id' => $class->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-slate-900 hover:bg-slate-800 transition-colors">
                            + Add Fee
                        </a>
                        @endcan
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($class->feeStructures as $fee)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 group">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $fee->fee_type }}</p>
                                    <p class="text-xs text-slate-500 font-medium">
                                        {{ number_format($fee->amount, 2) }} TK • 
                                        <span class="capitalize text-indigo-600 font-bold">{{ str_replace('_', ' ', $fee->frequency) }}</span>
                                    </p>
                                </div>
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @can('fee-structure-edit')
                                    <a href="{{ route('fee-structures.edit', $fee) }}" class="p-1 text-slate-400 hover:text-indigo-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 py-4 text-center">
                                <p class="text-sm text-slate-500 italic">No fees defined for this class yet.</p>
                                @can('fee-structure-create')
                                <a href="{{ route('fee-structures.create', ['class_id' => $class->id]) }}" class="mt-2 inline-block text-xs font-bold text-indigo-600 hover:underline">
                                    Click here to add the first fee
                                </a>
                                @endcan
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                 <!-- Students List -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-900">Enrolled Students</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ $class->students->count() }} / {{ $class->capacity }}
                        </span>
                    </div>
                    <ul role="list" class="divide-y divide-slate-100">
                        @forelse($class->students as $student)
                        <li class="px-6 py-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($student->photo)
                                        <img class="h-10 w-10 rounded-xl object-cover" src="{{ Storage::url($student->photo) }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-xl bg-slate-200 flex items-center justify-center text-slate-800 font-bold text-sm">
                                            {{ substr($student->first_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate">
                                        {{ $student->full_name }}
                                    </p>
                                    <p class="text-xs font-medium text-slate-500 truncate">
                                        ID: {{ $student->student_id }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">
                                    {{ $student->section->name ?? 'No Section' }}
                                </div>
                                <div>
                                    <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-3 py-1 border border-slate-300 text-xs font-bold rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-6 py-12 text-center text-slate-400 italic">
                            No students enrolled in this class yet.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Right Column: Stats & Teachers -->
            <div class="space-y-6">
                <!-- Class Teacher -->
                <div class="bg-slate-50 shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-medium leading-6 text-slate-900">Class Teacher</h3>
                    </div>
                    <div class="p-6">
                        @if($class->classTeacher)
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-slate-200">
                                         <svg class="h-full w-full text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-slate-900">{{ $class->classTeacher->name }}</h4>
                                    <p class="text-sm text-slate-500">{{ $class->classTeacher->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">No class teacher assigned.</p>
                        @endif
                    </div>
                </div>

                <!-- Sections Management -->
                <div class="bg-slate-50 shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                         <h3 class="text-lg font-medium leading-6 text-slate-900">Manage Sections</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Add Section Form -->
                        <form action="{{ route('classes.sections.store', $class) }}" method="POST" class="flex gap-2">
                            @csrf
                            <div class="flex-1">
                                <label for="section_name" class="sr-only">Section Name</label>
                                <input type="text" name="name" id="section_name" placeholder="e.g. Section A" required class="block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                Add
                            </button>
                        </form>

                        <!-- Sections List -->
                        <div class="space-y-3">
                             @forelse($class->sections as $section)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-200 group">
                                    <div class="flex items-center gap-3">
                                        <span class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-xs">
                                            {{ substr($section->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $section->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $section->students_count ?? 0 }} Students</p>
                                        </div>
                                    </div>
                                    
                                    @if($section->students_count == 0)
                                    <form action="{{ route('classes.sections.destroy', [$class, $section]) }}" method="POST" onsubmit="return confirm('Delete this section?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 italic text-center py-2">No sections created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                 <!-- Quick Stats -->
                <div class="bg-slate-100 rounded-xl p-6 border border-slate-100">
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Class Overview</h4>
                    <dl class="grid grid-cols-2 gap-6">
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Capacity</dt>
                            <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $class->capacity }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Status</dt>
                            <dd class="mt-1 text-lg font-bold {{ $class->is_active ? 'text-green-600' : 'text-slate-500' }}">
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
