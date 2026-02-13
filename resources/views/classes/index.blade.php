@extends('layouts.app')

@section('title', 'Classes - ERP System')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
             <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate tracking-tight">
                Academic Classes
            </h2>
             <p class="mt-1 text-sm text-slate-500">
                Manage your academic structure, classes, and sections.
            </p>
        </div>
        @can('class-create')
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('classes.create') }}" class="group inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5 text-slate-100 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create Class
            </a>
        </div>
        @endcan
    </div>

    <!-- Class Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($classes as $class)
        <div class="group rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 relative" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
            
            <!-- Colorful Top Bar (Randomize colors or based on ID) -->
            <div class="h-2 w-full bg-gradient-to-r from-slate-600 via-purple-500 to-pink-500"></div>

            <div class="p-5">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl font-bold transition-colors" style="color: rgb(var(--text-primary));">
                            <a href="{{ route('classes.show', $class) }}">{{ $class->name }}</a>
                        </h3>
                        <p class="text-xs font-mono mt-1" style="color: rgb(var(--text-tertiary));">{{ $class->code }}</p>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(var(--text-secondary))';" onmouseout="this.style.color='rgb(var(--text-tertiary));';">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                         <div x-show="open" class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-20 py-2" style="display: none; background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                             <a href="{{ route('classes.show', $class) }}" class="block px-4 py-2 text-sm transition-colors" style="color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))'; this.style.color='rgb(var(--text-primary))';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgb(var(--text-secondary))';" >
                                 View Details
                             </a>
                            @can('class-edit')
                            <a href="{{ route('classes.edit', $class) }}" class="block px-4 py-2 text-sm transition-colors" style="color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))'; this.style.color='rgb(var(--text-primary))';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgb(var(--text-secondary))';" >Edit Class</a>
                            @endcan
                            @can('class-delete')
                            <div class="my-1" style="border-top: 1px solid rgb(var(--border-primary));"></div>
                            <form action="{{ route('classes.destroy', $class) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50" onclick="return confirm('Are you sure?')">Delete Class</button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div class="rounded-xl p-3 text-center" style="background-color: rgb(var(--bg-secondary));">
                        <span class="block text-2xl font-bold" style="color: rgb(var(--text-primary));">{{ $class->students_count ?? 0 }}</span>
                        <span class="text-xs font-medium uppercase tracking-wide" style="color: rgb(var(--text-secondary));">Students</span>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background-color: rgb(var(--bg-secondary));">
                        <span class="block text-2xl font-bold" style="color: rgb(var(--text-primary));">{{ $class->capacity }}</span>
                        <span class="text-xs font-medium uppercase tracking-wide" style="color: rgb(var(--text-secondary));">Capacity</span>
                    </div>
                </div>

                <!-- Teacher -->
                <div class="flex items-center space-x-3 mb-4 p-2 rounded-xl transition-colors" style="background-color: transparent;" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))';" onmouseout="this.style.backgroundColor='transparent';">
                     <div class="flex-shrink-0">
                         <div class="h-8 w-8 rounded-full flex items-center justify-center" style="background-color: rgb(var(--bg-secondary)); color: rgb(var(--text-secondary));">
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                             </svg>
                         </div>
                     </div>
                     <div class="min-w-0">
                         <p class="text-xs font-medium uppercase" style="color: rgb(var(--text-secondary));">Class Teacher</p>
                         <p class="text-sm font-semibold truncate" style="color: rgb(var(--text-primary));">{{ $class->classTeacher->name ?? 'Not Assigned' }}</p>
                     </div>
                </div>

                <!-- Footer / Sections -->
                <div class="pt-4" style="border-top: 1px solid rgb(var(--border-primary));">
                    <p class="text-xs font-semibold uppercase mb-2" style="color: rgb(var(--text-tertiary));">Active Sections</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($class->sections->take(3) as $section)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium" style="background-color: rgb(var(--bg-secondary)); color: rgb(var(--text-secondary)); border: 1px solid rgb(var(--border-primary));">
                                {{ $section->name }}
                            </span>
                        @empty
                            <span class="text-xs italic" style="color: rgb(var(--text-tertiary));">No sections</span>
                        @endforelse
                        
                        @if($class->sections->count() > 3)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium" style="background-color: rgb(var(--bg-secondary)); color: rgb(var(--text-secondary));">
                                +{{ $class->sections->count() - 3 }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Inactive Overlay -->
             @if(!$class->is_active)
            <div class="absolute inset-x-0 top-0 bg-red-500 text-white text-xs font-bold px-3 py-1 text-center">
                INACTIVE
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-20 bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                <div class="mx-auto h-24 w-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="mt-2 text-lg font-bold text-slate-900">No classes found</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">Get started by creating your first academic class structure.</p>
                <div class="mt-8">
                    @can('class-create')
                    <a href="{{ route('classes.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-lg font-medium rounded-full shadow-sm text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all transform hover:scale-105">
                        Create New Class
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
            {{ $classes->links() }}
    </div>
</div>
@endsection
