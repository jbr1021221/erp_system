@extends('layouts.app')

@section('title', 'Select Fees - ' . $student->full_name)

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between w-full relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-slate-200 -z-10"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-indigo-200 -z-10" style="width: 25%;"></div>
                
                <!-- Step 1 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-semibold text-green-600">Basic Info</div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                        2
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-bold text-indigo-600">Select Fees</div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex flex-col items-center group">
                    <div class="h-10 w-10 bg-white border-2 border-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold border-4 border-white shadow transition-colors">
                        3
                    </div>
                    <div class="absolute top-12 w-32 text-center text-xs font-medium text-slate-500">Payment</div>
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
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                    Select Fees for {{ $student->full_name }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Class: <span class="font-semibold">{{ $student->class->name ?? 'N/A' }}</span> | 
                    Student ID: <span class="font-semibold">{{ $student->student_id }}</span>
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

        <!-- Fee Selection Form -->
        <form action="{{ route('students.store-fees', $student) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-bold text-slate-900">📋 Available Fees for {{ $student->class->name ?? 'this class' }}</h3>
                    <p class="mt-1 text-sm text-slate-600">Select which fees apply to this student and configure discounts if applicable.</p>
                </div>

                <div class="p-6">
                    @if($feeStructures->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No fees configured</h3>
                        <p class="mt-1 text-sm text-slate-500">Please configure fee structures for this class first.</p>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($feeStructures as $fee)
                        @php
                            $isAssigned = $student->feeAssignments->where('fee_structure_id', $fee->id)->first();
                        @endphp
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition-all duration-200" id="fee-{{ $fee->id }}">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="fees[]" value="{{ $fee->id }}" 
                                           id="fee_{{ $fee->id }}" 
                                           class="fee-checkbox h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded"
                                           {{ $isAssigned ? 'checked' : '' }}
                                           onchange="toggleFeeOptions({{ $fee->id }})">
                                </div>
                                <div class="ml-4 flex-1">
                                    <label for="fee_{{ $fee->id }}" class="font-semibold text-slate-900 text-lg cursor-pointer">
                                        {{ $fee->fee_type }}
                                    </label>
                                    <div class="mt-1 text-sm text-slate-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Amount: ৳{{ number_format($fee->amount, 2) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                            {{ ucfirst(str_replace('_', ' ', $fee->frequency)) }}
                                        </span>
                                        @if($fee->is_mandatory)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            Mandatory
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Discount Options (shown when checked) -->
                                    <div class="mt-4 fee-options" id="options-{{ $fee->id }}" style="display: {{ $isAssigned ? 'block' : 'none' }};">
                                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                            <h4 class="text-sm font-semibold text-slate-700 mb-3">💰 Discount Configuration</h4>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <!-- Discount Type -->
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 mb-1">Discount Type</label>
                                                    <select name="discount_type[{{ $fee->id }}]" 
                                                            class="discount-type block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                            onchange="toggleDiscountValue({{ $fee->id }})">
                                                        <option value="none" {{ $isAssigned && $isAssigned->discount_type == 'none' ? 'selected' : '' }}>No Discount</option>
                                                        <option value="percentage" {{ $isAssigned && $isAssigned->discount_type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                        <option value="fixed" {{ $isAssigned && $isAssigned->discount_type == 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
                                                    </select>
                                                </div>

                                                <!-- Discount Value -->
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 mb-1">Discount Value</label>
                                                    <input type="number" 
                                                           name="discount_value[{{ $fee->id }}]" 
                                                           id="discount_value_{{ $fee->id }}"
                                                           value="{{ $isAssigned ? $isAssigned->discount_value : 0 }}"
                                                           min="0" 
                                                           step="0.01"
                                                           class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                           placeholder="0.00">
                                                </div>

                                                <!-- Permanent Discount -->
                                                <div class="flex items-center">
                                                    <div class="flex items-center h-full">
                                                        <input type="checkbox" 
                                                               name="is_permanent[{{ $fee->id }}]" 
                                                               id="is_permanent_{{ $fee->id }}"
                                                               {{ $isAssigned && $isAssigned->is_permanent ? 'checked' : '' }}
                                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                                                        <label for="is_permanent_{{ $fee->id }}" class="ml-2 block text-sm text-slate-700">
                                                            <span class="font-medium">Permanent Discount</span>
                                                            <span class="block text-xs text-slate-500">Applies to all future payments</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Final Amount Preview -->
                                            <div class="mt-3 pt-3 border-t border-slate-200">
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-slate-600">Original Amount:</span>
                                                    <span class="font-semibold">৳{{ number_format($fee->amount, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between items-center text-sm mt-1" id="discount_preview_{{ $fee->id }}">
                                                    <span class="text-slate-600">Discount:</span>
                                                    <span class="font-semibold text-red-600">- ৳0.00</span>
                                                </div>
                                                <div class="flex justify-between items-center text-base mt-2 pt-2 border-t border-slate-200">
                                                    <span class="text-slate-900 font-bold">Final Amount:</span>
                                                    <span class="font-bold text-green-600" id="final_amount_{{ $fee->id }}">৳{{ number_format($fee->amount, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @error('fees')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6">
                <a href="{{ route('students.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancel
                </a>

                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all duration-200">
                    Continue to Payment
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleFeeOptions(feeId) {
    const checkbox = document.getElementById('fee_' + feeId);
    const options = document.getElementById('options-' + feeId);
    
    if (checkbox.checked) {
        options.style.display = 'block';
    } else {
        options.style.display = 'none';
    }
}

function toggleDiscountValue(feeId) {
    const discountType = document.querySelector(`select[name="discount_type[${feeId}]"]`).value;
    const discountValueInput = document.getElementById('discount_value_' + feeId);
    
    if (discountType === 'none') {
        discountValueInput.value = 0;
        discountValueInput.disabled = true;
    } else {
        discountValueInput.disabled = false;
    }
    
    updateFinalAmount(feeId);
}

function updateFinalAmount(feeId) {
    const feeCard = document.getElementById('fee-' + feeId);
    const originalAmount = parseFloat(feeCard.querySelector('.bg-green-100').textContent.replace(/[^0-9.]/g, ''));
    const discountType = document.querySelector(`select[name="discount_type[${feeId}]"]`).value;
    const discountValue = parseFloat(document.getElementById('discount_value_' + feeId).value) || 0;
    
    let discountAmount = 0;
    
    if (discountType === 'percentage') {
        discountAmount = (originalAmount * discountValue) / 100;
    } else if (discountType === 'fixed') {
        discountAmount = discountValue;
    }
    
    const finalAmount = Math.max(0, originalAmount - discountAmount);
    
    document.getElementById('discount_preview_' + feeId).querySelector('.text-red-600').textContent = '- ৳' + discountAmount.toFixed(2);
    document.getElementById('final_amount_' + feeId).textContent = '৳' + finalAmount.toFixed(2);
}

// Add event listeners to all discount inputs
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.discount-type').forEach(select => {
        select.addEventListener('change', function() {
            const feeId = this.name.match(/\d+/)[0];
            updateFinalAmount(feeId);
        });
    });
    
    document.querySelectorAll('input[name^="discount_value"]').forEach(input => {
        input.addEventListener('input', function() {
            const feeId = this.name.match(/\d+/)[0];
            updateFinalAmount(feeId);
        });
    });
});
</script>
@endpush
@endsection
