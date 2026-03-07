@extends('layouts.app')
@section('page-title', 'Add New Student')
@section('breadcrumb', 'Academics · Students · New')

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Classes</a>
@endsection

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6 animate-in">
  <div>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
      <div style="width:36px;height:36px;border-radius:10px;background:rgba(5,150,105,0.12);display:flex;align-items:center;justify-content:center;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
      </div>
      <h1 style="font-family:'Outfit',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Add New Student</h1>
    </div>
    <p style="font-size:13px;color:var(--text-muted);padding-left:46px;">Fill in the details below to register a new student in the system</p>
  </div>
  <a href="{{ route('students.index') }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-secondary);text-decoration:none;transition:all 0.2s;"
     onmouseover="this.style.borderColor='var(--accent)';this.style.color='var(--accent)'"
     onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-secondary)'">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Students
  </a>
</div>

{{-- Validation Errors Banner --}}
@if($errors->any())
<div class="animate-in delay-1" style="margin-bottom:20px;padding:14px 18px;background:rgba(225,29,72,0.08);border:1px solid rgba(225,29,72,0.25);border-radius:12px;display:flex;align-items:flex-start;gap:12px;">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>
    <p style="font-size:13px;font-weight:600;color:#e11d48;margin-bottom:4px;">Please fix the following errors:</p>
    <ul style="list-style:disc;padding-left:16px;margin:0;">
      @foreach($errors->all() as $error)
        <li style="font-size:12px;color:var(--text-secondary);margin-top:2px;">{{ $error }}</li>
      @endforeach
    </ul>
  </div>
</div>
@endif

<form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" id="studentForm">
  @csrf
  @include('students._form')
</form>

@endsection
