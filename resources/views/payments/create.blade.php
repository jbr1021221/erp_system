@extends('layouts.app')

@section('title', 'Collect Fee - ERP System')

@section('header_title', 'Fee Collection')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate tracking-tight">
                New Fee Collection
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ 
        studentId: '{{ $student ? $student->id : '' }}',
        payableFees: @js($payableFees ?? []),
        cartItems: [],
        tempFeeId: '',
        tempSelectedPeriodValues: [],
        amount: 0,
        
        get tempFee() {
            return this.payableFees.find(f => f.id == this.tempFeeId);
        },

        isPeriodInCart(feeId, periodValue) {
            return this.cartItems.some(item => item.fee_structure_id === feeId && item.billing_month === periodValue);
        },

        addToCart() {
            if (!this.tempFee || this.tempSelectedPeriodValues.length === 0) return;
            
            this.tempSelectedPeriodValues.forEach(periodVal => {
                if (this.isPeriodInCart(this.tempFee.id, periodVal)) return;
                
                // Find label for period
                const periodObj = this.tempFee.available_periods.find(p => p.value == periodVal);
                const label = periodObj ? periodObj.label : periodVal;

                this.cartItems.push({
                    fee_structure_id: this.tempFee.id,
                    fee_type: this.tempFee.fee_type,
                    billing_month: periodVal,
                    period_label: label,
                    amount: this.tempFee.unit_amount
                });
            });
            
            // Reset selection
            this.tempSelectedPeriodValues = [];
            this.tempFeeId = '';
            
            this.calculateTotal();
        },

        removeFromCart(index) {
            this.cartItems.splice(index, 1);
            this.calculateTotal();
        },

        calculateTotal() {
            this.amount = this.cartItems.reduce((sum, item) => sum + parseFloat(item.amount), 0).toFixed(2);
        },
        
        generateHiddenInputs() {
            let html = '';
            this.cartItems.forEach((item, index) => {
                html += `<input type='hidden' name='items[${index}][fee_structure_id]' value='${item.fee_structure_id}'>`;
                html += `<input type='hidden' name='items[${index}][fee_type]' value='${item.fee_type}'>`;
                html += `<input type='hidden' name='items[${index}][billing_month]' value='${item.billing_month}'>`;
                html += `<input type='hidden' name='items[${index}][amount]' value='${item.amount}'>`;
            });
            return html;
        },

        formatAmount(value) {
            return new Intl.NumberFormat('en-BD', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
        }
    }">
        <form action="{{ route('payments.store') }}" method="POST" class="p-6 space-y-8">
            @csrf
            
            <!-- Section 1: Student Selection -->
            <div class="bg-indigo-50/50 p-6 rounded-xl border border-indigo-100">
                <label for="student_id" class="block text-sm font-bold text-indigo-900 mb-2">Select Student</label>
                <select name="student_id" id="student_id" required 
                    class="block w-full rounded-xl border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-white"
                    @change="window.location.href = '{{ route('payments.create') }}?student_id=' + $event.target.value">
                    <option value="">Search or Select Student...</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}" {{ ($student && $student->id == $s->id) ? 'selected' : '' }}>
                        {{ $s->student_id }} - {{ $s->full_name }} ({{ $s->class->name ?? 'N/A' }})
                    </option>
                    @endforeach
                </select>
            </div>

            @if($student)
            <!-- Section 2: Fee Cart Interface -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Selection Area -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm h-full">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 pb-3 border-b border-slate-100">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                            Add Fee to Cart
                        </h3>
                        
                        <!-- 1. Select Fee Type -->
                        <div class="space-y-3 mb-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Fee Category</label>
                            <select x-model="tempFeeId" @change="tempSelectedPeriodValues = []" 
                                class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3">
                                <option value="">Select Category...</option>
                                <template x-for="fee in payableFees" :key="fee.id">
                                    <option :value="fee.id" x-text="fee.fee_type + ' (' + fee.frequency + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- 2. Select Periods (Only shows when fee selected) -->
                        <div x-show="tempFee" class="space-y-3" x-transition>
                            <div class="flex justify-between items-center">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Select Periods</label>
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-100" 
                                      x-text="tempFee ? formatAmount(tempFee.unit_amount) + ' TK' : ''"></span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto pr-1">
                                <template x-for="period in tempFee?.available_periods" :key="period.value">
                                    <label class="relative flex items-center group cursor-pointer select-none">
                                        <input type="checkbox" 
                                            class="peer sr-only"
                                            :value="period.value" 
                                            x-model="tempSelectedPeriodValues"
                                            :disabled="isPeriodInCart(tempFeeId, period.value)">
                                        <div class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm font-medium transition-all text-center
                                                    peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900 peer-checked:shadow-md
                                                    peer-disabled:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:bg-slate-50 peer-disabled:text-slate-400
                                                    hover:border-indigo-400">
                                            <span x-text="period.label"></span>
                                        </div>
                                        <div x-show="isPeriodInCart(tempFeeId, period.value)" class="absolute -top-1 -right-1 text-green-500 bg-white rounded-full shadow-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- 3. Add Button -->
                        <button type="button" @click="addToCart()" 
                            :disabled="!tempFeeId || tempSelectedPeriodValues.length === 0"
                            class="w-full mt-8 inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed transition-all transform active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Right Column: Payment List (Cart) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col">
                        <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <span class="bg-indigo-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                Review Payment List
                            </h3>
                            <span class="text-xs font-semibold text-slate-500" x-show="cartItems.length > 0">
                                <span x-text="cartItems.length"></span> items
                            </span>
                        </div>

                        <div class="flex-1 overflow-y-auto p-0 min-h-[300px]">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50 sticky top-0 backdrop-blur-sm">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fee</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Period</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Remove</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    <template x-for="(item, index) in cartItems" :key="index">
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800" x-text="item.fee_type"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100" x-text="item.period_label"></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 text-right font-bold" x-text="formatAmount(item.amount)"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <button type="button" @click="removeFromCart(index)" class="text-slate-300 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="cartItems.length === 0">
                                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="bg-slate-50 p-4 rounded-full mb-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </div>
                                                <p class="font-medium">Cart is empty</p>
                                                <p class="text-xs mt-1 text-slate-400">Add fees from the left panel.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-slate-50 border-t border-slate-200" x-show="cartItems.length > 0">
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-right text-sm font-bold text-slate-900 uppercase">Total Payable</td>
                                        <td class="px-6 py-4 text-right text-xl font-extrabold text-indigo-600" x-text="amount + ' TK'"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Payment Details -->
            <div class="bg-white p-6 rounded-xl border border-slate-200">
                <h4 class="font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Payment Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Payment Date -->
                    <div class="space-y-2">
                        <label for="payment_date" class="block text-sm font-semibold text-slate-700">Date Collected</label>
                        <input type="date" name="payment_date" id="payment_date" required value="{{ date('Y-m-d') }}"
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-2">
                        <label for="payment_method" class="block text-sm font-semibold text-slate-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" required 
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="online">Online</option>
                            <option value="cheque">Cheque</option>
                            <option value="card">Card</option>
                        </select>
                    </div>

                    <!-- Transaction Reference -->
                    <div class="space-y-2">
                        <label for="transaction_reference" class="block text-sm font-semibold text-slate-700">Reference / Check No.</label>
                        <input type="text" name="transaction_reference" id="transaction_reference" 
                            placeholder="Optional"
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50">
                    </div>
                </div>
                
                <div class="mt-4 space-y-2">
                    <label for="remarks" class="block text-sm font-semibold text-slate-700">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="2" 
                        placeholder="Any additional notes..."
                        class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50"></textarea>
                </div>
            </div>

            <!-- Hidden Inputs & Actions -->
            <div x-html="generateHiddenInputs()"></div>
            <input type="hidden" name="billing_year" value="{{ date('Y') }}"> 
            <input type="hidden" name="amount" x-model="amount">

            <div class="flex justify-between items-center pt-4">
                <p class="text-sm text-slate-500 italic">
                    * Please review the cart before confirming.
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        :disabled="cartItems.length === 0"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed transition-all duration-200">
                        Confirm Payment
                    </button>
                </div>
            </div>
            @else
            <!-- No Student Selected Placeholder -->
            <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="text-lg font-medium text-slate-900">Select a Student</h3>
                <p class="text-slate-500">Please select a student above to proceed with fee collection.</p>
            </div>
            @endif

        </form>
    </div>

    @if($student)
    <!-- Recent History Sidebar / Quick Ledger -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-6">
            <h4 class="text-sm font-bold text-indigo-900 uppercase mb-4">Student Info</h4>
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 text-xl font-bold">
                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">{{ $student->full_name }}</h4>
                    <p class="text-xs text-slate-500">{{ $student->student_id }} • {{ $student->class->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h4 class="text-sm font-bold text-slate-800 uppercase mb-4">Recent Payments</h4>
            <div class="space-y-3">
                @forelse($student->payments()->latest()->take(3)->get() as $payment)
                <div class="flex justify-between items-center text-xs">
                    <div>
                        <p class="font-bold text-slate-700">{{ $payment->fee_type }}</p>
                        <p class="text-slate-400">{{ $payment->payment_date->format('d M, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-indigo-600">{{ number_format($payment->amount, 2) }} TK</p>
                        <p class="text-[10px] text-slate-400 uppercase">{{ $payment->billing_year }} - Month: {{ $payment->billing_month ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">No payments found.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
