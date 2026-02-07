@extends('layouts.app')

@section('title', 'Add Teacher - ERP System')

@section('header_title', 'Faculty Management')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Add New Teacher</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Onboard a new faculty member to the system</p>
        </div>
        <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>

    <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-slate-900 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Personal Information</h2>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe" 
                           class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                    @error('name') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Designation -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Designation <span class="text-red-500">*</span></label>
                    <input type="text" name="designation" value="{{ old('designation') }}" required placeholder="e.g. Senior Lecturer" 
                           class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                    @error('designation') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. john@example.com" 
                           class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                    @error('email') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. +1234567890" 
                           class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                    @error('phone') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Join Date -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Joining Date <span class="text-red-500">*</span></label>
                    <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required 
                           class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                    @error('join_date') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Salary -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Monthly Salary <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm font-bold">$</span>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" required placeholder="0.00" 
                               class="w-full pl-8 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                    </div>
                    @error('salary') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Account Status <span class="text-red-500">*</span></label>
                    <select name="is_active" required class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Photo -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Profile Photo</label>
                    <input type="file" name="photo" accept="image/*" 
                           class="w-full px-4 py-2.5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-slate-900 file:text-white hover:file:bg-slate-800">
                    <p class="text-[10px] text-slate-400 font-bold ml-1 uppercase tracking-widest">JPG, PNG up to 2MB</p>
                    @error('photo') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Address -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Address</label>
                    <textarea name="address" rows="3" placeholder="Residential address..." 
                              class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-slate-900 transition-all">{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-50 flex items-center justify-end gap-3">
                <button type="reset" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-100 transition-all">
                    Reset Form
                </button>
                <button type="submit" class="px-8 py-3 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                    Save Teacher Record
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
