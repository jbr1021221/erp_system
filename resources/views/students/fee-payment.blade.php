@extends('layouts.app')

@section('title', 'Fee & Payment - ' . $student->first_name . ' ' . $student->last_name)

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800'}}">Classes</a>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Progress Steps (4 Steps) -->
    <div class="mb-8">
        <div class="flex items-center justify-between w-full relative">
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-slate-200 -z-10"></div>
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-emerald-500 -z-10" style="width: 33.33%;"></div>
            
            <!-- Step 1 -->
            <div class="relative flex flex-col items-center group">
                <div class="h-10 w-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="absolute top-12 w-32 text-center text-xs font-semibold text-emerald-600">Basic Info</div>
            </div>

            <!-- Step 2 -->
            <div class="relative flex flex-col items-center group">
                <div class="h-10 w-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold border-4 border-white shadow transition-colors">
                    2
                </div>
                <div class="absolute top-12 w-32 text-center text-xs font-bold text-emerald-600">Fee & Payment</div>
            </div>

            <!-- Step 3 -->
            <div class="relative flex flex-col items-center group">
                <div class="h-10 w-10 bg-white border-2 border-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold border-4 border-white shadow transition-colors">
                    3
                </div>
                <div class="absolute top-12 w-32 text-center text-xs font-medium text-slate-500">Preview</div>
            </div>

            <!-- Step 4 -->
            <div class="relative flex flex-col items-center group">
                <div class="h-10 w-10 bg-white border-2 border-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold border-4 border-white shadow transition-colors">
                    4
                </div>
                <div class="absolute top-12 w-32 text-center text-xs font-medium text-slate-500">Complete</div>
            </div>
        </div>
        <div class="h-8"></div>
    </div>

    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-800 sm:text-3xl sm:truncate">
                Fee Assignment & Initial Payment
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Student: <span class="font-semibold">{{ $student->first_name }} {{ $student->last_name }}</span> | 
                Class: <span class="font-semibold">{{ $student->class->name ?? 'N/A' }}</span>
            </p>
        </div>
    </div>

    <form action="{{ route('students.store-fee-payment', $student) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Side: Fee Selection (8/12) -->
            <div class="lg:col-span-7">
                <div class="card p-0 overflow-hidden" style="border-top: 3px solid var(--accent);">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-800">📋 Step 2a: Applicable Fees</h3>
                        <p class="text-xs text-slate-500">Select and configure discounts for mandatory and optional fees.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($feeStructures as $fee)
                        <div class="fee-card border border-slate-200 rounded-xl p-4 transition-all hover:border-emerald-300 hover:shadow-sm" id="fee-row-{{ $fee->id }}">
                            <div class="flex items-start gap-4">
                                <div class="pt-1">
                                    <input type="checkbox" name="fees[]" value="{{ $fee->id }}" 
                                           id="fee_{{ $fee->id }}" class="fee-checkbox h-5 w-5 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
                                           {{ $fee->is_mandatory ? 'checked' : '' }}
                                           onchange="toggleFeeOptions({{ $fee->id }})">
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <label for="fee_{{ $fee->id }}" class="font-bold text-slate-800 cursor-pointer">{{ $fee->fee_type }}</label>
                                        <span class="font-bold text-emerald-600">৳{{ number_format($fee->amount, 0) }}</span>
                                    </div>
                                    <div class="flex gap-2 mt-1">
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200">{{ str_replace('_', ' ', $fee->frequency) }}</span>
                                        @if($fee->is_mandatory)
                                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-600 border border-amber-200">Mandatory</span>
                                        @endif
                                    </div>

                                    <!-- Discount Row (Hidden if unchecked) -->
                                    <div class="mt-4 pt-4 border-t border-slate-100 fee-options {{ $fee->is_mandatory ? '' : 'hidden' }}" id="options-{{ $fee->id }}">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Discount</label>
                                                <div class="flex gap-2">
                                                    <select name="discount_type[{{ $fee->id }}]" class="text-xs border-slate-200 rounded-lg bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500" onchange="calculateFee({{ $fee->id }})">
                                                        <option value="none">None</option>
                                                        <option value="percentage">Percent %</option>
                                                        <option value="fixed">Fixed ৳</option>
                                                    </select>
                                                    <input type="number" name="discount_value[{{ $fee->id }}]" value="0" class="w-20 text-xs border-slate-200 rounded-lg bg-slate-50" oninput="calculateFee({{ $fee->id }})">
                                                </div>
                                            </div>
                                            <div class="text-right flex flex-col justify-end">
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Final Fee</span>
                                                <span class="font-bold text-slate-800" id="final-{{ $fee->id }}">৳{{ number_format($fee->amount, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Payment Form (4/12) -->
            <div class="lg:col-span-5">
                <div class="card p-0 overflow-hidden sticky top-24" style="border-top: 3px solid var(--accent-gold);">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-lg font-bold text-slate-800">💰 Step 2b: Initial Payment</h3>
                        <p class="text-xs text-slate-500">Record any payment received during admission.</p>
                    </div>
                    <div class="p-6 space-y-5">
                        
                        <!-- Total to Pay Summary -->
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-emerald-700 uppercase">Total Selected Fees</span>
                                <span class="text-lg font-black text-emerald-800" id="total-selected">৳0.00</span>
                            </div>
                            <p class="text-[10px] text-emerald-600 font-medium">Sum of all currently checked and discounted fees.</p>
                        </div>

                        <!-- Amount Field -->
                        <div>
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 block">Payment Amount <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">৳</span>
                                <input type="number" name="payment_amount" id="payment_amount" step="0.01" required
                                       class="w-full pl-9 pr-4 py-3 border-slate-200 rounded-xl text-lg font-bold text-slate-800 focus:ring-emerald-500 focus:border-emerald-500"
                                       placeholder="0.00">
                            </div>
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="setFullAmount()" class="text-[10px] font-bold uppercase px-2 py-1 bg-slate-100 hover:bg-emerald-100 text-slate-600 rounded transition-colors">Pay Full</button>
                                <button type="button" onclick="document.getElementById('payment_amount').value=0" class="text-[10px] font-bold uppercase px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded transition-colors">Clear</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Method -->
                            <div>
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 block">Method</label>
                                <select name="payment_method" class="w-full border-slate-200 rounded-xl text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <!-- Date -->
                            <div>
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 block">Date</label>
                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                                       class="w-full border-slate-200 rounded-xl text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 block">Payment Notes</label>
                            <textarea name="notes" rows="2" class="w-full border-slate-200 rounded-xl text-sm p-3 focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g. Received by cashier..."></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2 group">
                                Save & Preview Admission
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const fees = {!! json_encode($feeStructures->keyBy('id')) !!};

    function toggleFeeOptions(id) {
        const row = document.getElementById('options-' + id);
        if (document.getElementById('fee_' + id).checked) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
        updateTotals();
    }

    function calculateFee(id) {
        const type = document.querySelector(`select[name="discount_type[${id}]"]`).value;
        const val = parseFloat(document.querySelector(`input[name="discount_value[${id}]"]`).value) || 0;
        const original = fees[id].amount;
        
        let final = original;
        if (type === 'percentage') {
            final = original - (original * val / 100);
        } else if (type === 'fixed') {
            final = original - val;
        }
        
        document.getElementById('final-' + id).innerText = '৳' + Math.max(0, final).toLocaleString();
        updateTotals();
    }

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('.fee-checkbox:checked').forEach(cb => {
            const id = cb.value;
            const type = document.querySelector(`select[name="discount_type[${id}]"]`).value;
            const val = parseFloat(document.querySelector(`input[name="discount_value[${id}]"]`).value) || 0;
            const original = fees[id].amount;
            
            let final = original;
            if (type === 'percentage') {
                final = original - (original * val / 100);
            } else if (type === 'fixed') {
                final = original - val;
            }
            total += Math.max(0, final);
        });
        
        document.getElementById('total-selected').innerText = '৳' + total.toLocaleString();
    }

    function setFullAmount() {
        const totalText = document.getElementById('total-selected').innerText;
        const amount = parseFloat(totalText.replace('৳', '').replace(/,/g, ''));
        document.getElementById('payment_amount').value = amount;
    }

    // Initialize totals on load
    document.addEventListener('DOMContentLoaded', () => {
        updateTotals();
    });
</script>
@endpush
@endsection
