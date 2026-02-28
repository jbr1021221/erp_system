@extends('layouts.app')
@section('page-title', 'Edit Student')
@section('breadcrumb', 'Academics · Students · Edit')

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('teachers.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('teachers.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Teachers</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800'}}">Classes</a>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Edit Student</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Updating: <strong>{{ $student->name }}</strong></p>
  </div>
  <a href="{{ route('students.show', $student) }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-secondary);text-decoration:none;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> View Profile
  </a>
</div>
<form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
  @csrf @method('PUT')
  @include('students._form')
</form>
@endsection
