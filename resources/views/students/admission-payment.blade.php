@extends('layouts.app')

@section('title', 'Admission Payment - ' . $student->full_name)

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between w-full relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-slate-200 -z-10"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-indigo-200 -z-10" style="width: 50%;"></div>
                
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
                    <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        3
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-bold text-indigo-600">Payment</div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-white border-2 border-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold border-4 border-white shadow transition-colors">
                        4
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-medium text-slate-500">Preview</div>
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
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-800 sm:text-3xl sm:truncate">
                    💳 Admission Payment
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Student: <span class="font-semibold">{{ $student->full_name }}</span> | 
                    Class: <span class="font-semibold">{{ $student->class->name ?? 'N/A' }}</span>
                </p>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Payment Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('students.store-admission-payment', $student) }}" method="POST" id="paymentForm">
                    @csrf

                    <!-- Admission Fee Card -->
                    @if($admissionFee)
                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-green-50 to-emerald-50">
                            <h3 class="text-lg font-bold text-slate-800">🎓 Admission Fee</h3>
                            <p class="mt-1 text-sm text-slate-600">Pay full or partial amount now</p>
                        </div>

                        <div class="p-6">
                            <input type="hidden" name="fee_structure_id" value="{{ $admissionFee->fee_structure_id }}">
                            
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6 mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-slate-700 font-medium">{{ $admissionFee->feeStructure->fee_type }}</span>
                                    <span class="text-2xl font-bold text-indigo-600">৳{{ number_format($admissionFee->getFinalAmount(), 2) }}</span>
                                </div>

                                @if($admissionFee->discount_type !== 'none')
                                <div class="text-sm text-slate-600 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Original Amount:</span>
                                        <span class="line-through">৳{{ number_format($admissionFee->feeStructure->amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-red-600">
                                        <span>Discount ({{ $admissionFee->discount_type === 'percentage' ? $admissionFee->discount_value . '%' : '৳' . number_format($admissionFee->discount_value, 2) }}):</span>
                                        <span>- ৳{{ number_format($admissionFee->getDiscountAmount(), 2) }}</span>
                                    </div>
                                    @if($admissionFee->is_permanent)
                                    <div class="mt-2 pt-2 border-t border-indigo-200">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            🔄 Permanent Discount
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <!-- Payment Amount -->
                            <div class="mb-6">
                                <label for="payment_amount" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Payment Amount <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 sm:text-sm">৳</span>
                                    </div>
                                    <input type="number" 
                                           name="payment_amount" 
                                           id="payment_amount" 
                                           value="{{ old('payment_amount', $admissionFee->getFinalAmount()) }}"
                                           max="{{ $admissionFee->getFinalAmount() }}"
                                           min="0"
                                           step="0.01"
                                           required
                                           class="block w-full pl-8 pr-12 py-3 border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg font-semibold"
                                           oninput="updatePaymentSummary()">
                                </div>
                                <p class="mt-2 text-sm text-slate-500">
                                    💡 You can pay partial amount. Remaining balance can be paid later.
                                </p>
                                @error('payment_amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quick Amount Buttons -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Quick Select:</label>
                                <div class="grid grid-cols-4 gap-2">
                                    <button type="button" onclick="setAmount({{ $admissionFee->getFinalAmount() * 0.25 }})" class="px-3 py-2 bg-slate-100 hover:bg-indigo-100 text-slate-700 hover:text-indigo-700 rounded-lg text-sm font-medium transition-colors">
                                        25%
                                    </button>
                                    <button type="button" onclick="setAmount({{ $admissionFee->getFinalAmount() * 0.50 }})" class="px-3 py-2 bg-slate-100 hover:bg-indigo-100 text-slate-700 hover:text-indigo-700 rounded-lg text-sm font-medium transition-colors">
                                        50%
                                    </button>
                                    <button type="button" onclick="setAmount({{ $admissionFee->getFinalAmount() * 0.75 }})" class="px-3 py-2 bg-slate-100 hover:bg-indigo-100 text-slate-700 hover:text-indigo-700 rounded-lg text-sm font-medium transition-colors">
                                        75%
                                    </button>
                                    <button type="button" onclick="setAmount({{ $admissionFee->getFinalAmount() }})" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        Full
                                    </button>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-6">
                                <label for="payment_method" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Payment Method <span class="text-red-500">*</span>
                                </label>
                                <select name="payment_method" id="payment_method" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>📝 Cheque</option>
                                    <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>💳 Online Payment</option>
                                </select>
                                @error('payment_method')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div class="mb-6">
                                <label for="payment_date" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Payment Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="payment_date" 
                                       id="payment_date" 
                                       value="{{ old('payment_date', date('Y-m-d')) }}"
                                       required
                                       class="block w-full rounded-xl border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('payment_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                                    Notes (Optional)
                                </label>
                                <textarea name="notes" 
                                          id="notes" 
                                          rows="3" 
                                          class="block w-full rounded-xl border-slate-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    No admission fee found. Please go back and select an admission fee.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('students.select-fees', $student) }}" 
                           class="inline-flex items-center px-6 py-3 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Fees
                        </a>

                        @if($admissionFee)
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all duration-200">
                            Process Payment & Continue
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Right Column: Summary & Other Fees -->
            <div class="lg:col-span-1">
                <!-- Payment Summary -->
                @if($admissionFee)
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden mb-6 sticky top-6">
                    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <h3 class="text-lg font-bold text-slate-800">📊 Payment Summary</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Total Admission Fee:</span>
                            <span class="font-semibold">৳<span id="total_fee">{{ number_format($admissionFee->getFinalAmount(), 2) }}</span></span>
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Paying Now:</span>
                            <span class="font-semibold text-green-600">৳<span id="paying_now">{{ number_format($admissionFee->getFinalAmount(), 2) }}</span></span>
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-800 font-bold">Remaining Balance:</span>
                                <span class="text-lg font-bold text-red-600">৳<span id="remaining_balance">0.00</span></span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Can be paid in future payments</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Other Fees -->
                @if($otherFees->isNotEmpty())
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-800">📝 Other Selected Fees</h3>
                        <p class="mt-1 text-xs text-slate-600">To be paid later</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($otherFees as $fee)
                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-800">{{ $fee->feeStructure->fee_type }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ ucfirst(str_replace('_', ' ', $fee->feeStructure->frequency)) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-slate-800">৳{{ number_format($fee->getFinalAmount(), 2) }}</p>
                                        @if($fee->discount_type !== 'none')
                                        <p class="text-xs text-red-600">-৳{{ number_format($fee->getDiscountAmount(), 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Total Other Fees:</span>
                                <span class="text-base font-bold text-slate-800">৳{{ number_format($otherFees->sum(fn($f) => $f->getFinalAmount()), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const totalFee = {{ $admissionFee ? $admissionFee->getFinalAmount() : 0 }};

function setAmount(amount) {
    document.getElementById('payment_amount').value = amount.toFixed(2);
    updatePaymentSummary();
}

function updatePaymentSummary() {
    const payingNow = parseFloat(document.getElementById('payment_amount').value) || 0;
    const remaining = Math.max(0, totalFee - payingNow);
    
    document.getElementById('paying_now').textContent = payingNow.toFixed(2);
    document.getElementById('remaining_balance').textContent = remaining.toFixed(2);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePaymentSummary();
});
</script>
@endpush
@endsection
