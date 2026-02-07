@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-50 overflow-hidden shadow-sm sm:rounded-xl">
            <div class="p-6 text-slate-900">
                <h1 class="text-2xl font-bold mb-4">Welcome, {{ auth()->user()->name }}!</h1>
                <p>You are logged in as: <span class="font-semibold">{{ $stats['role'] ?? 'User' }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
