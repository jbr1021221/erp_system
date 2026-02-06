@extends('layouts.app')

@section('title', 'Students List')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Students</h1>
    @can('student-create')
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Student
        </a>
    </div>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('students.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, ID or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Class</th>
                <th>Guardian</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr>
                <td>{{ $student->student_id }}</td>
                <td>
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="Photo" class="rounded-circle" width="40" height="40">
                    @else
                        <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center text-white" style="width: 40px; height: 40px;">
                            {{ substr($student->first_name, 0, 1) }}
                        </div>
                    @endif
                </td>
                <td>
                    <div class="fw-bold">{{ $student->full_name }}</div>
                    <small class="text-muted">{{ $student->email }}</small>
                </td>
                <td>
                    {{ $student->class ? $student->class->name : 'N/A' }} 
                    @if($student->section)
                        ({{ $student->section->name }})
                    @endif
                </td>
                <td>
                    <div>{{ $student->guardian_name }}</div>
                    <small class="text-muted">{{ $student->guardian_phone }}</small>
                </td>
                <td>
                    @php
                        $statusClass = match($student->status) {
                            'active' => 'success',
                            'inactive' => 'secondary',
                            'suspended' => 'danger',
                            'graduated' => 'info',
                            default => 'primary'
                        };
                    @endphp
                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($student->status) }}</span>
                </td>
                <td>
                    <div class="btn-group">
                        @can('student-view')
                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endcan
                        
                        @can('student-edit')
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        
                        @can('student-delete')
                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {{ $students->withQueryString()->links() }}
</div>
@endsection
