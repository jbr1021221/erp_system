@extends('layouts.app')
@section('page-title', 'Collect Fee')
@section('breadcrumb', 'Finance · Income · Collect')
@section('content')

<div class="flex items-center justify-between mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Collect Fee</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Record a new fee payment for a student</p>
  </div>
  <a href="{{ route('payments.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-secondary);text-decoration:none;">
    ← Back to Payments
  </a>
</div>

<form method="POST" action="{{ route('payments.store') }}">
  @csrf
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main form (2/3) --}}
    <div class="lg:col-span-2 flex flex-col gap-5">

      {{-- Student Search --}}
      <div class="card p-6 animate-in delay-1">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Select Student</h2>
        </div>
        <div>
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Student *</label>
          <select name="student_id" required
            style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;"
            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
            <option value="">Search and select student...</option>
            @foreach($students ?? [] as $student)
              <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} — {{ $student->schoolClass->name ?? '' }} ({{ $student->student_id ?? '' }})
              </option>
            @endforeach
          </select>
          @error('student_id')<p style="font-size:11px;color:var(--accent);margin-top:4px;">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Fee Breakdown --}}
      <div class="card p-6 animate-in delay-2">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent-green);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Fee Details</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Fee Type *</label>
            <select name="fee_type_id" required style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <option value="">Select Fee Type</option>
              @foreach($feeTypes ?? [] as $ft)
                <option value="{{ $ft->id }}" {{ old('fee_type_id') == $ft->id ? 'selected' : '' }}>{{ $ft->name }}</option>
              @endforeach
            </select>
          </div>
          <x-form-field name="month_year" label="Month / Period" type="month" :value="old('month_year', date('Y-m'))" />
          <x-form-field name="amount_due" label="Amount Due (৳) *" type="number" :value="old('amount_due', '')" placeholder="0.00" required />
          <x-form-field name="discount" label="Discount (৳)" type="number" :value="old('discount', '0')" placeholder="0.00" />
          <x-form-field name="amount_paid" label="Amount Paying (৳) *" type="number" :value="old('amount_paid', '')" placeholder="0.00" required />
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Payment Method *</label>
            <select name="payment_method" required style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <option value="">Select Method</option>
              <option value="cash" {{ old('payment_method')=='cash' ? 'selected' : '' }}>Cash</option>
              <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
              <option value="mobile_banking" {{ old('payment_method')=='mobile_banking' ? 'selected' : '' }}>Mobile Banking (bKash/Nagad)</option>
              <option value="cheque" {{ old('payment_method')=='cheque' ? 'selected' : '' }}>Cheque</option>
            </select>
          </div>
          <x-form-field name="transaction_id" label="Transaction / Receipt ID" type="text" :value="old('transaction_id', '')" placeholder="Optional reference" />
          <x-form-field name="payment_date" label="Payment Date *" type="date" :value="old('payment_date', date('Y-m-d'))" required />
        </div>
        <div class="mt-4">
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Notes</label>
          <textarea name="notes" rows="2" placeholder="Optional notes..."
            style="width:100%;padding:12px 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;resize:none;font-family:'DM Sans',sans-serif;"
            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ old('notes') }}</textarea>
        </div>
      </div>
    </div>

    {{-- Summary Sidebar (1/3) --}}
    <div class="flex flex-col gap-5">
      {{-- Live Summary --}}
      <div class="card p-6 animate-in delay-1" style="border-top:3px solid var(--accent-green);">
        <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);margin-bottom:16px;">Payment Summary</h3>
        <div class="flex flex-col gap-3">
          <div class="flex justify-between items-center">
            <span style="font-size:13px;color:var(--text-secondary);">Amount Due</span>
            <span id="summaryDue" style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">৳0</span>
          </div>
          <div class="flex justify-between items-center">
            <span style="font-size:13px;color:var(--text-secondary);">Discount</span>
            <span id="summaryDiscount" style="font-family:'Syne',sans-serif;font-weight:600;font-size:15px;color:var(--accent-gold);">-৳0</span>
          </div>
          <div style="height:1px;background:var(--border-color);"></div>
          <div class="flex justify-between items-center">
            <span style="font-size:13px;color:var(--text-secondary);">Net Payable</span>
            <span id="summaryNet" style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text-primary);">৳0</span>
          </div>
          <div class="flex justify-between items-center">
            <span style="font-size:13px;color:var(--text-secondary);">Paying Now</span>
            <span id="summaryPaying" style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--accent-green);">৳0</span>
          </div>
          <div style="height:1px;background:var(--border-color);"></div>
          <div class="flex justify-between items-center">
            <span style="font-size:14px;font-weight:600;color:var(--text-primary);">Balance</span>
            <span id="summaryBalance" style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:var(--accent);">৳0</span>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="card p-5 animate-in delay-2">
        <button type="submit"
          style="width:100%;height:50px;background:var(--accent);color:white;border:none;border-radius:12px;font-family:'Syne',sans-serif;font-weight:700;font-size:15px;cursor:pointer;transition:all 0.2s;margin-bottom:10px;"
          onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
          Record Payment
        </button>
        <button type="submit" name="print_receipt" value="1"
          style="width:100%;height:44px;background:var(--bg-surface-2);color:var(--text-secondary);border:1px solid var(--border-color);border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;margin-bottom:10px;"
          onmouseover="this.style.background='var(--border-color)'" onmouseout="this.style.background='var(--bg-surface-2)'">
          Save & Print Receipt
        </button>
        <a href="{{ route('payments.index') }}"
           style="display:block;text-align:center;height:40px;line-height:40px;border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;">
          Cancel
        </a>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
function updateSummary() {
  const due = parseFloat(document.querySelector('[name=amount_due]')?.value) || 0;
  const disc = parseFloat(document.querySelector('[name=discount]')?.value) || 0;
  const paying = parseFloat(document.querySelector('[name=amount_paid]')?.value) || 0;
  const net = Math.max(due - disc, 0);
  const balance = Math.max(net - paying, 0);
  document.getElementById('summaryDue').textContent = '৳' + due.toLocaleString();
  document.getElementById('summaryDiscount').textContent = '-৳' + disc.toLocaleString();
  document.getElementById('summaryNet').textContent = '৳' + net.toLocaleString();
  document.getElementById('summaryPaying').textContent = '৳' + paying.toLocaleString();
  document.getElementById('summaryBalance').textContent = '৳' + balance.toLocaleString();
  document.getElementById('summaryBalance').style.color = balance > 0 ? 'var(--accent)' : 'var(--accent-green)';
}
document.querySelectorAll('[name=amount_due],[name=discount],[name=amount_paid]').forEach(el => el.addEventListener('input', updateSummary));
</script>
@endpush

@endsection
