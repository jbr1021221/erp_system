@extends('layouts.app')

@section('title', 'Admission Complete - ' . $student->full_name)

@section('content')
<div class="py-6 no-print">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between w-full relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-slate-200 -z-10"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-green-200 -z-10" style="width: 100%;"></div>
                
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
                    <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-semibold text-green-600">Preview</div>
                </div>

                <!-- Step 5 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-bold text-indigo-600">Complete</div>
                </div>
            </div>
            <div class="h-8"></div> <!-- Spacer for labels -->
        </div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-slate-900">Admission Completed Successfully!</h2>
            <p class="mt-2 text-lg text-slate-600">
                Student <span class="font-bold">{{ $student->full_name }}</span> has been admitted to Class <span class="font-bold">{{ $student->class->name ?? 'N/A' }}</span>.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center gap-4 mb-10">
            <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-6 py-3 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                View Student Profile
            </a>
            
            <button onclick="window.print()" class="inline-flex items-center px-8 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Documents
            </button>
            
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Admission
            </a>
        </div>
    </div>
</div>

<!-- Print Container -->
<div class="print-container">
    <!-- Receipt Section -->
    <div class="receipt-section">
        <h2 class="text-center text-xl font-bold mb-4 no-print text-gray-400">--- Payment Receipt ---</h2>
        @include('students.partials.horizontal-receipt', ['student' => $student, 'payment' => $payment])
    </div>
    
    <!-- Page Break -->
    <div class="page-break"></div>
    
    <!-- Admission Form Section -->
    <div class="form-section">
        <h2 class="text-center text-xl font-bold mb-4 no-print text-gray-400 mt-8">--- Admission Form ---</h2>
        @include('students.partials.admission-form-content', ['student' => $student])
    </div>
</div>

<style>
    @media print {
        @page {
            margin: 0;
            size: auto;
        }
        
        body * {
            visibility: hidden;
        }
        
        .no-print {
            display: none !important;
        }
        
        .print-container, .print-container * {
            visibility: visible;
        }
        
        .print-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        .receipt-section {
            width: 100%;
            height: 100vh; /* Force landscape feel or separate page */
            page-break-after: always;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .form-section {
            width: 100%;
            page-break-before: always;
        }
        
        /* Reset any conflicting styles */
        .a4-page {
            box-shadow: none !important;
            margin: 0 auto !important;
        }
    }
    
    /* Screen styles */
    .print-container {
        max-width: 250mm;
        margin: 0 auto;
        background: #f1f5f9;
        padding: 40px;
        border-radius: 8px;
    }
    
    .receipt-section, .form-section {
        background: white;
        margin-bottom: 40px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 0;
    }
    
    .receipt-section {
        padding: 20px;
    }
</style>
@endsection
