@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('header_title', 'Payment Receipt')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Receipt Container -->
    <div id="receipt-content" class="bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
        
        <!-- Receipt Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ config('app.name', 'ERP System') }}</h1>
                    <p class="text-indigo-100 text-sm">Payment Receipt</p>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-xs text-indigo-100 uppercase tracking-wider">Receipt No.</p>
                        <p class="text-xl font-bold">{{ $receiptNumber }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Body -->
        <div class="p-8">
            <!-- Student & Payment Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Student Information -->
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Student Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500">Student Name</p>
                            <p class="text-base font-bold text-slate-800">{{ $payment->student->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Student ID</p>
                            <p class="text-base font-semibold text-slate-700">{{ $payment->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Class</p>
                            <p class="text-base font-semibold text-slate-700">{{ $payment->student->class->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                    <h3 class="text-sm font-bold text-indigo-900 uppercase tracking-wider mb-4">Payment Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-indigo-600">Payment Date</p>
                            <p class="text-base font-bold text-indigo-900">{{ $payment->payment_date->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-600">Payment Method</p>
                            <p class="text-base font-semibold text-indigo-800 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                        </div>
                        @if($payment->transaction_reference)
                        <div>
                            <p class="text-xs text-indigo-600">Transaction Reference</p>
                            <p class="text-base font-semibold text-indigo-800">{{ $payment->transaction_reference }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-xs text-indigo-600">Received By</p>
                            <p class="text-base font-semibold text-indigo-800">{{ $payment->receivedBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fee Details Table -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 pb-2 border-b-2 border-slate-200">Fee Details</h3>
                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fee Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Period</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($payments as $index => $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-800">{{ $item->fee_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    @if($item->billing_month)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ DateTime::createFromFormat('!m', $item->billing_month)->format('F') }} {{ $item->billing_year }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ $item->billing_year }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800 text-right">{{ number_format($item->amount, 2) }} TK</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-indigo-50 border-t-2 border-indigo-200">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-base font-bold text-indigo-900 uppercase">Total Amount Paid</td>
                                <td class="px-6 py-4 text-right text-xl font-extrabold text-indigo-600">{{ number_format($totalAmount, 2) }} TK</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Remarks (if any) -->
            @if($payment->remarks)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">Remarks</h4>
                <p class="text-sm text-amber-800">{{ $payment->remarks }}</p>
            </div>
            @endif

            <!-- Footer Note -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <p class="text-xs text-slate-500 text-center">
                    This is a computer-generated receipt and does not require a signature. 
                    For any queries, please contact the accounts department.
                </p>
                <p class="text-xs text-slate-400 text-center mt-2">
                    Generated on {{ now()->format('d M, Y h:i A') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between items-center gap-4 print:hidden">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Payments
        </a>
        
        <div class="flex gap-3">
            <button onclick="window.print()" class="inline-flex items-center px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Receipt
            </button>
            
            <button onclick="downloadPDF()" class="inline-flex items-center px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Dark Mode Overrides for Receipt */
    [data-theme="dark"] #receipt-content {
        background-color: rgb(30 41 59) !important; /* slate-800 */
        border-color: rgb(51 65 85) !important; /* slate-700 */
    }
    
    [data-theme="dark"] #receipt-content .bg-slate-50 {
        background-color: rgb(15 23 42) !important; /* slate-900 */
        border-color: rgb(51 65 85) !important;
    }
    
    [data-theme="dark"] #receipt-content .border-slate-200,
    [data-theme="dark"] #receipt-content .divide-slate-200 {
        border-color: rgb(51 65 85) !important; /* slate-700 */
    }
    
    [data-theme="dark"] #receipt-content .divide-slate-100 {
        border-color: rgb(30 41 59) !important; /* slate-800 */
    }
    
    [data-theme="dark"] #receipt-content .text-slate-500,
    [data-theme="dark"] #receipt-content .text-slate-400 {
        color: rgb(148 163 184) !important; /* slate-400 */
    }
    
    [data-theme="dark"] #receipt-content .text-slate-700,
    [data-theme="dark"] #receipt-content .text-slate-800 {
        color: rgb(248 250 252) !important; /* slate-50 */
    }
    
    [data-theme="dark"] #receipt-content .text-slate-600 {
        color: rgb(203 213 225) !important; /* slate-300 */
    }
    
    [data-theme="dark"] #receipt-content .bg-white {
        background-color: rgb(30 41 59) !important; /* slate-800 */
    }
    
    [data-theme="dark"] #receipt-content tbody {
        background-color: rgb(30 41 59) !important; /* slate-800 */
    }
    
    [data-theme="dark"] #receipt-content .bg-indigo-50 {
        background-color: rgb(30 27 75) !important; /* indigo-950 */
    }
    
    [data-theme="dark"] #receipt-content .border-indigo-100 {
        border-color: rgb(55 48 163) !important; /* indigo-800 */
    }
    
    [data-theme="dark"] #receipt-content .text-indigo-900 {
        color: rgb(238 242 255) !important; /* indigo-50 */
    }
    
    [data-theme="dark"] #receipt-content .text-indigo-600 {
        color: rgb(165 180 252) !important; /* indigo-300 */
    }
    
    [data-theme="dark"] #receipt-content .text-indigo-800 {
        color: rgb(199 210 254) !important; /* indigo-200 */
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        #receipt-content, #receipt-content * {
            visibility: visible;
        }
        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
        }
        .print\\:hidden {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function downloadPDF() {
        // Simple print-to-PDF approach
        // For more advanced PDF generation, you can use libraries like jsPDF or server-side PDF generation
        window.print();
    }
</script>
@endpush
@endsection
