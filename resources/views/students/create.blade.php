@extends('layouts.app')
@section('page-title', 'Add Student')
@section('breadcrumb', 'Academics · Students · New')

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('teachers.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('teachers.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Teachers</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800'}}">Classes</a>
@endsection

@section('content')
<div class="flex flex-col md:flex-row md:items-start md:justify-between mb-8 pb-5 border-b border-slate-200 dark:border-slate-800">
  <div>
    <h1 class="text-xl font-semibold text-slate-800 dark:text-slate-100">Add New Student</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Fill in the details to register a new student</p>
  </div>
</div>
<form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
  @csrf
  @include('students._form')
</form>
@endsection
