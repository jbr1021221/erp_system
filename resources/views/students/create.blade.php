@extends('layouts.app')
@section('page-title', 'Add Student')
@section('breadcrumb', 'Academics · Students · New')
@section('content')
<div class="mb-6">
  <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Add New Student</h1>
  <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Fill in the details to register a new student</p>
</div>
<form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
  @csrf
  @include('students._form')
</form>
@endsection
