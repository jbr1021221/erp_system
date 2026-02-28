@extends('layouts.app')
@section('page-title', 'Expenses Report')
@section('breadcrumb', 'Reports · Expenses')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">Expenses Report</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">Expenses by category, date range and status</p>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 14px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='var(--bg-surface)'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg> Back
    </a>
    <button onclick="window.print()" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);cursor:pointer;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='var(--bg-surface)'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg> Print
    </button>
  </div>
</div>

{{-- Filters --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;padding:16px;margin-bottom:16px;">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div class="min-w-[150px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">From</label>
      <input type="date" name="from" value="{{ $from }}"
        style="height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;transition:border-color 0.2s;"
        onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='var(--border-color)'">
    </div>
    <div class="min-w-[150px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">To</label>
      <input type="date" name="to" value="{{ $to }}"
        style="height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;transition:border-color 0.2s;"
        onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='var(--border-color)'">
    </div>
    @if($categories->isNotEmpty())
    <div class="min-w-[160px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Category</label>
      <select name="category_id" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>
    @endif
    <div class="min-w-[140px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Status</label>
      <select name="status" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Status</option>
        <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}>Pending</option>
        <option value="approved" {{ request('status')=='approved' ? 'selected':'' }}>Approved</option>
        <option value="paid"     {{ request('status')=='paid'     ? 'selected':'' }}>Paid</option>
        <option value="rejected" {{ request('status')=='rejected' ? 'selected':'' }}>Rejected</option>
      </select>
    </div>
    <div class="min-w-[150px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Method</label>
      <select name="payment_method" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Methods</option>
        <option value="cash"          {{ request('payment_method')=='cash'          ? 'selected':'' }}>Cash</option>
        <option value="bank_transfer" {{ request('payment_method')=='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
        <option value="cheque"        {{ request('payment_method')=='cheque'        ? 'selected':'' }}>Cheque</option>
        <option value="card"          {{ request('payment_method')=='card'          ? 'selected':'' }}>Card</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" style="height:40px;padding:0 18px;background:linear-gradient(135deg,#f59e0b,#fbbf24);color:white;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">Filter</button>
      <a href="{{ route('reports.expenses') }}" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;">Clear</a>
    </div>
  </form>
</div>

@php
  $statusColors = [
    'pending'  => ['bg'=>'rgba(251,191,36,0.12)',  'border'=>'rgba(251,191,36,0.3)',  'text'=>'#fbbf24'],
    'approved' => ['bg'=>'rgba(74,222,128,0.12)',   'border'=>'rgba(74,222,128,0.3)',  'text'=>'#4ade80'],
    'paid'     => ['bg'=>'rgba(99,102,241,0.12)',   'border'=>'rgba(99,102,241,0.3)',  'text'=>'#818cf8'],
    'rejected' => ['bg'=>'rgba(248,113,113,0.12)',  'border'=>'rgba(248,113,113,0.3)', 'text'=>'#f87171'],
  ];
  $methodColors = ['cash'=>'#4ade80','bank_transfer'=>'#38bdf8','cheque'=>'#fbbf24','card'=>'#a78bfa'];
@endphp

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Total Amount</div>
    <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:#f87171;">৳{{ number_format($total, 0) }}</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Transactions</div>
    <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:#f59e0b;">{{ $expenses->count() }}</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Pending</div>
    <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:#fbbf24;">{{ $pending }}</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Top Category</div>
    <div style="font-size:15px;font-weight:800;font-family:'Syne',sans-serif;color:#fb923c;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $topCategory }}</div>
  </div>
</div>

{{-- Category breakdown --}}
@if($byCategory->isNotEmpty())
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;padding:18px 20px;margin-bottom:16px;">
  <div style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">By Category</div>
  <div class="space-y-3">
    @foreach($byCategory as $cat => $amt)
    @php $pct = $total > 0 ? round($amt / $total * 100) : 0; @endphp
    <div>
      <div class="flex justify-between items-center" style="margin-bottom:5px;">
        <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">{{ $cat }}</span>
        <span style="font-size:13px;font-weight:700;color:var(--text-primary);">৳{{ number_format($amt,0) }} <span style="font-size:11px;color:var(--text-muted);">({{ $pct }}%)</span></span>
      </div>
      <div style="height:6px;background:var(--bg-surface-2);border-radius:99px;overflow:hidden;">
        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#f59e0b,#fb923c);border-radius:99px;"></div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif

{{-- Table --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
  <div class="overflow-x-auto">
    <table class="w-full" style="border-collapse:collapse;">
      <thead>
        <tr style="background:var(--bg-surface-2);border-bottom:1px solid var(--border-color);">
          @foreach(['#','Voucher','Description','Category','Method','Status','Date','Amount'] as $h)
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:{{ $h=='Amount'?'right':'left' }};">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($expenses as $i => $expense)
        @php
          $sc = $statusColors[$expense->status] ?? $statusColors['pending'];
          $mc = $methodColors[$expense->payment_method] ?? '#94a3b8';
        @endphp
        <tr style="border-bottom:1px solid var(--border-color);transition:background 0.15s;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='transparent'">
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ $i + 1 }}</td>
          <td style="padding:12px 16px;font-size:11px;color:var(--text-muted);font-family:monospace;">{{ $expense->voucher_number }}</td>
          <td style="padding:12px 16px;font-size:13px;color:var(--text-primary);max-width:200px;">
            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $expense->description }}</div>
            @if($expense->payment_reference)
              <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">Ref: {{ $expense->payment_reference }}</div>
            @endif
          </td>
          <td style="padding:12px 16px;">
            <span style="display:inline-block;padding:2px 9px;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.25);border-radius:6px;font-size:11px;font-weight:600;color:#f59e0b;">
              {{ optional($expense->category)->name ?? '—' }}
            </span>
          </td>
          <td style="padding:12px 16px;">
            <span style="display:inline-block;padding:2px 9px;background:{{ $mc }}18;border:1px solid {{ $mc }}35;border-radius:6px;font-size:11px;font-weight:600;color:{{ $mc }};">
              {{ ucwords(str_replace('_', ' ', $expense->payment_method)) }}
            </span>
          </td>
          <td style="padding:12px 16px;">
            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 9px;background:{{ $sc['bg'] }};border:1px solid {{ $sc['border'] }};border-radius:6px;font-size:11px;font-weight:600;color:{{ $sc['text'] }};">
              <span style="width:5px;height:5px;border-radius:50%;background:{{ $sc['text'] }};"></span>
              {{ ucfirst($expense->status) }}
            </span>
          </td>
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($expense->expense_date)->format('M j, Y') }}</td>
          <td style="padding:12px 16px;font-size:14px;font-weight:700;color:#f87171;text-align:right;">৳{{ number_format($expense->amount, 0) }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="padding:50px;text-align:center;color:var(--text-muted);font-size:13px;">No expenses found for selected period</td></tr>
        @endforelse
      </tbody>
      @if($expenses->count())
      <tfoot>
        <tr style="background:var(--bg-surface-2);border-top:2px solid var(--border-color);">
          <td colspan="7" style="padding:12px 16px;font-size:13px;font-weight:700;color:var(--text-primary);">Total ({{ $expenses->count() }} records)</td>
          <td style="padding:12px 16px;font-size:15px;font-weight:800;color:#f87171;text-align:right;font-family:'Syne',sans-serif;">৳{{ number_format($total, 0) }}</td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>

@endsection