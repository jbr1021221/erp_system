@extends('layouts.app')

@section('title', 'Class Details - ERP System')

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('teachers.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('teachers.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Teachers</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800'}}">Classes</a>
@endsection


@section('content')

    
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-start justify-between mb-8 pb-5 border-b border-slate-200 dark:border-slate-800">
    <div>
        <h1 class="text-xl font-semibold text-slate-800 dark:text-slate-100">{{ $class->name }}
                </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Code: {{ $class->code }} | Year: {{ $class->academic_year }}
                </p>
    </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('classes.index') }}" class="mr-3 inline-flex items-center px-4 py-2 rounded-xl shadow-sm text-sm font-medium transition-colors" style="border: 1px solid rgb(var(--border-primary)); background-color: rgb(var(--bg-elevated)); color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))';" onmouseout="this.style.backgroundColor='rgb(var(--bg-elevated));';">
                    Back to List
                </a>
                @can('class-edit')
                <a href="{{ route('classes.edit', $class) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Edit Class
                </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Class Info & Sections -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Fee Structure -->
                <div class="shadow-sm rounded-xl overflow-hidden" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
                    <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid rgb(var(--border-primary)); background-color: rgb(var(--bg-secondary));">  
                        <h3 class="text-lg font-bold" style="color: rgb(var(--text-primary));">Fee Structure</h3>
                        @can('fee-structure-create')
                        <a href="{{ route('fee-structures.create', ['class_id' => $class->id]) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                            + Add Fee
                        </a>
                        @endcan
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($class->feeStructures as $fee)
                            <div class="flex items-center justify-between p-4 rounded-xl group" style="background-color: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                                <div>
                                    <p class="text-sm font-bold" style="color: rgb(var(--text-primary));">{{ $fee->fee_type }}</p>
                                    <p class="text-xs font-medium" style="color: rgb(var(--text-secondary));">
                                        {{ number_format($fee->amount, 2) }} TK • 
                                        <span class="capitalize text-indigo-600 font-bold">{{ str_replace('_', ' ', $fee->frequency) }}</span>
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    @can('fee-structure-edit')
                                    <a href="{{ route('fee-structures.edit', $fee) }}" class="p-1 transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(99 102 241)';" onmouseout="this.style.color='rgb(var(--text-tertiary));';">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 py-4 text-center">
                                <p class="text-sm italic" style="color: rgb(var(--text-secondary));">No fees defined for this class yet.</p>
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
                <div class="shadow-sm rounded-xl overflow-hidden" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
                    <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid rgb(var(--border-primary)); background-color: rgb(var(--bg-secondary));">  
                        <h3 class="text-lg font-bold" style="color: rgb(var(--text-primary));">Enrolled Students</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ $class->students->count() }} / {{ $class->capacity }}
                        </span>
                    </div>
                    <ul role="list" style="border-color: rgb(var(--border-primary));" class="divide-y">
                        @forelse($class->students as $student)
                        <li class="px-6 py-4 transition-colors" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))';" onmouseout="this.style.backgroundColor='transparent';">
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
                                    <p class="text-sm font-bold truncate" style="color: rgb(var(--text-primary));">
                                        {{ $student->full_name }}
                                    </p>
                                    <p class="text-xs font-medium truncate" style="color: rgb(var(--text-secondary));">
                                        ID: {{ $student->student_id }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">
                                    {{ $student->section->name ?? 'No Section' }}
                                </div>
                                <div>
                                    <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-lg transition-colors" style="border: 1px solid rgb(var(--border-primary)); background-color: rgb(var(--bg-elevated)); color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))';" onmouseout="this.style.backgroundColor='rgb(var(--bg-elevated));';">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-6 py-12 text-center italic" style="color: rgb(var(--text-tertiary));">
                            No students enrolled in this class yet.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Right Column: Stats & Teachers -->
            <div class="space-y-6">
                <!-- Class Teacher -->
                <div class="shadow-sm rounded-xl overflow-hidden" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
                    <div class="px-6 py-4" style="border-bottom: 1px solid rgb(var(--border-primary));">  
                        <h3 class="text-lg font-medium leading-6" style="color: rgb(var(--text-primary));">Class Teacher</h3>
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
                                    <h4 class="text-lg font-medium" style="color: rgb(var(--text-primary));">{{ $class->classTeacher->name }}</h4>
                                    <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $class->classTeacher->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm italic" style="color: rgb(var(--text-secondary));">No class teacher assigned.</p>
                        @endif
                    </div>
                </div>

                <!-- Sections Management -->
                <div class="shadow-sm rounded-xl overflow-hidden" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
                    <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid rgb(var(--border-primary));">  
                         <h3 class="text-lg font-medium leading-6" style="color: rgb(var(--text-primary));">Manage Sections</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Add Section Form -->
                        <form action="{{ route('classes.sections.store', $class) }}" method="POST" class="flex gap-2">
                            @csrf
                            <div class="flex-1">
                                <label for="section_name" class="sr-only">Section Name</label>
                                <input type="text" name="name" id="section_name" placeholder="e.g. Section A" required class="block w-full rounded-md shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" style="background-color: rgb(var(--bg-elevated)); color: rgb(var(--text-primary)); border-color: rgb(var(--border-primary));">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                Add
                            </button>
                        </form>

                        <!-- Sections List -->
                        <div class="space-y-3">
                             @forelse($class->sections as $section)
                                <div class="flex items-center justify-between p-3 rounded-xl group" style="background-color: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                                    <div class="flex items-center gap-3">
                                        <span class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-xs">
                                            {{ substr($section->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium" style="color: rgb(var(--text-primary));">{{ $section->name }}</p>
                                            <p class="text-xs" style="color: rgb(var(--text-secondary));">{{ $section->students_count ?? 0 }} Students</p>
                                        </div>
                                    </div>
                                    
                                    @if($section->students_count == 0)
                                    <form action="{{ route('classes.sections.destroy', [$class, $section]) }}" method="POST" onsubmit="return confirm('Delete this section?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(220 38 38)';" onmouseout="this.style.color='rgb(var(--text-tertiary));';">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm italic text-center py-2" style="color: rgb(var(--text-secondary));">No sections created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                 <!-- Quick Stats -->
                <div class="rounded-xl p-6" style="background-color: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4" style="color: rgb(var(--text-primary));">Class Overview</h4>
                    <dl class="grid grid-cols-2 gap-6">
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Capacity</dt>
                            <dd class="mt-1 text-2xl font-bold" style="color: rgb(var(--text-primary));">{{ $class->capacity }}</dd>
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
    @endsection
