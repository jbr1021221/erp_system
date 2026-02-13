@extends('layouts.app')

@section('title', 'Fee Structures - ERP System')

@section('header_title', 'Fee Management')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate tracking-tight">
                Class-wise Fee Structures
            </h2>
        </div>
        @can('fee-structure-create')
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('fee-structures.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Fee
            </a>
        </div>
        @endcan
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($classes as $class)
        <div class="rounded-xl shadow-sm overflow-hidden flex flex-col" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary));">
            <div class="px-6 py-4 flex justify-between items-center" style="border-bottom: 1px solid rgb(var(--border-primary)); background-color: rgb(var(--bg-secondary));">
                <h3 class="text-lg font-bold" style="color: rgb(var(--text-primary));">{{ $class->name }}</h3>
                <span class="px-2 py-1 rounded-md text-[10px] uppercase font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                    {{ $class->feeStructures->count() }} Fees
                </span>
            </div>
            <div class="flex-1 p-6 space-y-4">
                @forelse($class->feeStructures as $fee)
                <div class="flex justify-between items-start group">
                    <div>
                        <p class="text-sm font-bold" style="color: rgb(var(--text-secondary));">{{ $fee->fee_type }}</p>
                        <p class="text-xs font-medium" style="color: rgb(var(--text-tertiary));">
                            {{ number_format($fee->amount, 2) }} TK • 
                            <span class="capitalize text-indigo-600">{{ str_replace('_', ' ', $fee->frequency) }}</span>
                        </p>
                        <p class="text-[10px] font-mono" style="color: rgb(var(--text-tertiary));">{{ $fee->academic_year }}</p>
                    </div>
                    <div class="flex gap-1">
                        @can('fee-structure-edit')
                        <a href="{{ route('fee-structures.edit', $fee) }}" class="p-1.5 rounded-lg transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(99 102 241)'; this.style.backgroundColor='rgb(238 242 255)';" onmouseout="this.style.color='rgb(var(--text-tertiary))'; this.style.backgroundColor='transparent';">
                            <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        @endcan
                        @can('fee-structure-delete')
                        <form action="{{ route('fee-structures.destroy', $fee) }}" method="POST" onsubmit="return confirm('Delete this fee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(244 63 94)'; this.style.backgroundColor='rgb(255 241 242)';" onmouseout="this.style.color='rgb(var(--text-tertiary))'; this.style.backgroundColor='transparent';">
                                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-xs italic" style="color: rgb(var(--text-tertiary));">No fees defined for this class</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
