@extends('layouts.app')

@section('title', 'Preview Admission Form - ' . $student->full_name)

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8 print:hidden">
            <div class="flex items-center justify-between w-full relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-slate-200 -z-10"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-indigo-200 -z-10" style="width: 75%;"></div>
                
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-semibold text-green-600">Basic Info</div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-semibold text-green-600">Select Fees</div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-semibold text-green-600">Payment</div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        4
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-bold text-indigo-600">Preview</div>
                </div>

                <!-- Step 5 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-white border-2 border-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold border-4 border-white shadow transition-colors">
                        5
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-medium text-slate-500">Complete</div>
                </div>
            </div>
            <div class="h-8"></div> <!-- Spacer for labels -->
        </div>

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8 print:hidden">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-800 sm:text-3xl sm:truncate">
                    📄 Preview Admission Form
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Review details before finalizing admission.
                </p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('students.admission-payment', $student) }}" 
                   class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                    Back to Payment
                </a>
                <a href="{{ route('students.admission-complete', $student) }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:-translate-y-0.5 transition-all duration-200">
                    Confirm & Complete
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </a>
            </div>
        </div>
        
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg print:hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Preview Container -->
        <div class="border border-slate-300 shadow-xl rounded-lg overflow-hidden bg-slate-500/10 p-4 lg:p-8">
            <div class="transform scale-95 origin-top mx-auto bg-white shadow-2xl">
                <!-- Include the admission form partial/content directly to avoid iframe issues -->
                <div class="admission-form-wrapper">
                    @include('students.partials.admission-form-content', ['student' => $student])
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end print:hidden">
            <a href="{{ route('students.admission-complete', $student) }}" 
               class="inline-flex items-center px-8 py-4 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:-translate-y-0.5 transition-all duration-200">
                Confirm & Complete Admission
                <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<style>
    /* Adjust the admission form when included in preview */
    .admission-form-wrapper {
        width: 210mm;
        margin: 0 auto;
        background: white;
    }
    .admission-form-wrapper .print-button {
        display: none !important;
    }
    .admission-form-wrapper body {
        background: transparent !important;
        padding: 0 !important;
    }
    .admission-form-wrapper .a4-page {
        margin: 0 !important;
        box-shadow: none !important;
    }
</style>
@endsection
