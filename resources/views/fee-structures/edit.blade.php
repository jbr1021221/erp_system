@extends('layouts.app')

@section('title', 'Edit Fee - ERP System')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
  <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Expenses</a>
@endsection


@section('header_title', 'Fee Management')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-semibold text-slate-800 tracking-tight">
                Edit Fee Definition
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('fee-structures.update', $feeStructure) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class Selection -->
                <div class="space-y-2">
                    <label for="class_id" class="block text-sm font-semibold text-slate-700">Class</label>
                    <select name="class_id" id="class_id" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $feeStructure->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fee Type -->
                <div class="space-y-2">
                    <label for="fee_type" class="block text-sm font-semibold text-slate-700">Fee Name</label>
                    <input type="text" name="fee_type" id="fee_type" required value="{{ old('fee_type', $feeStructure->fee_type) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                </div>

                <!-- Amount -->
                <div class="space-y-2">
                    <label for="amount" class="block text-sm font-semibold text-slate-700">Amount (TK)</label>
                    <input type="number" step="0.01" name="amount" id="amount" required value="{{ old('amount', $feeStructure->amount) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                </div>

                <!-- Frequency -->
                <div class="space-y-2">
                    <label for="frequency" class="block text-sm font-semibold text-slate-700">Billing Cycle</label>
                    <select name="frequency" id="frequency" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                        @foreach($frequencies as $key => $label)
                        <option value="{{ $key }}" {{ old('frequency', $feeStructure->frequency) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Academic Year -->
                <div class="space-y-2">
                    <label for="academic_year" class="block text-sm font-semibold text-slate-700">Academic Year</label>
                    <input type="text" name="academic_year" id="academic_year" required value="{{ old('academic_year', $feeStructure->academic_year) }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                </div>

                <!-- Mandatory -->
                <div class="flex items-center space-x-3 pt-8">
                    <input type="hidden" name="is_mandatory" value="0">
                    <input type="checkbox" name="is_mandatory" id="is_mandatory" value="1" {{ old('is_mandatory', $feeStructure->is_mandatory) ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_mandatory" class="text-sm font-semibold text-slate-700">Is Mandatory?</label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('fee-structures.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-all duration-200">
                    Update Fee Structure
                </button>
            </div>
        </form>
    @endsection
