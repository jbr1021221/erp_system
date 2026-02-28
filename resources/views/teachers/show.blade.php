@extends('layouts.app')

@section('title', 'Teacher Profile - ERP System')

@section('header_title', 'Faculty Management')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Profile Header -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden relative">
        <div class="h-32 bg-slate-900 border-b border-slate-800 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 to-indigo-900 opacity-50"></div>
            <div class="absolute -right-10 -top-10 h-64 w-64 bg-white/5 rounded-full blur-3xl"></div>
        </div>
        <div class="px-8 pb-8 flex flex-col md:flex-row items-end gap-6 -mt-12 relative z-10">
            <div class="relative">
                @if($teacher->photo)
                    <img src="{{ Storage::url($teacher->photo) }}" class="h-32 w-32 rounded-3xl object-cover ring-8 ring-white shadow-xl shadow-slate-200">
                @else
                    <div class="h-32 w-32 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-4xl ring-8 ring-white shadow-xl shadow-slate-200 uppercase">
                        {{ substr($teacher->name, 0, 1) }}
                    </div>
                @endif
                <div class="absolute -bottom-2 -right-2 h-8 w-8 rounded-xl bg-emerald-500 border-4 border-white flex items-center justify-center text-white" title="Active Account">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="flex-1 space-y-2">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $teacher->name }}</h1>
                        <p class="text-sm font-bold text-indigo-600 uppercase tracking-widest">{{ $teacher->designation }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @can('teacher-edit')
                        <a href="{{ route('teachers.edit', $teacher) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                            Edit Profile
                        </a>
                        @endcan
                        <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Contact Box -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3 h-3 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    Contact Details
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Email Address</p>
                            <p class="text-sm font-bold text-slate-700">{{ $teacher->email ?? 'Not Available' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Phone Number</p>
                            <p class="text-sm font-bold text-slate-700">{{ $teacher->phone }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Residential Address</p>
                            <p class="text-sm font-bold text-slate-700 leading-snug">{{ $teacher->address ?? 'Not Provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Info -->
        <div class="md:col-span-2 space-y-6">
            <!-- Professional Info -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center justify-between border-b border-slate-50 pb-6">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                        <span class="h-2 w-2 rounded-full bg-slate-900"></span>
                        Professional Profile
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Joining Date</p>
                        <p class="text-lg font-bold text-slate-800">{{ $teacher->join_date->format('F d, Y') }}</p>
                        <p class="text-xs text-slate-400 font-medium tracking-tight">Part of institution for {{ $teacher->join_date->diffForHumans(null, true) }}</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Monthly Salary</p>
                        <p class="text-lg font-bold text-slate-800">${{ number_format($teacher->salary, 2) }}</p>
                        <p class="text-[10px] px-2 py-0.5 inline-block bg-indigo-50 text-indigo-600 rounded-md font-black uppercase tracking-widest">Standard Package</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Designation</p>
                        <div class="flex items-center gap-2">
                             <p class="text-lg font-bold text-slate-800">{{ $teacher->designation }}</p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Employee Status</p>
                        <div>
                            @if($teacher->is_active)
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black uppercase tracking-widest">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                    Active / On Duty
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest">
                                    Inactive / Absent
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Experience/Bio (Optional/Placeholder for now) -->
            <div class="bg-slate-50 p-8 rounded-3xl border border-dashed border-slate-200">
                <div class="flex flex-col items-center justify-center text-center space-y-3">
                    <div class="h-12 w-12 rounded-2xl bg-white flex items-center justify-center text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-400">No additional professional bio or experience details provided for this teacher.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
