@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Teacher Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ auth()->user()->name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
             <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 font-medium">My Classes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['my_classes'] }}</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 font-medium">Total Students</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_students'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">My Classes</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($myClasses as $class)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                        <h4 class="text-lg font-bold text-gray-900">{{ $class->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">Students: {{ $class->students->count() }}</p>
                        <div class="mt-4">
                            <a href="{{ route('students.index', ['class_id' => $class->id]) }}" class="text-sm text-blue-600 hover:text-blue-800">View Students &rarr;</a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center text-gray-500">
                        No classes assigned to you.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
