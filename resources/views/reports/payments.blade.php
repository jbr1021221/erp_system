@extends('layouts.app')
@section('page-title', 'Payments Report')
@section('breadcrumb', 'Reports · Payments')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">Payments Report</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">All payment records with filters</p>
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
        onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='var(--border-color)'">
    </div>
    <div class="min-w-[150px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">To</label>
      <input type="date" name="to" value="{{ $to }}"
        style="height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;transition:border-color 0.2s;"
        onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='var(--border-color)'">
    </div>
    <div class="min-w-[140px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Method</label>
      <select name="method" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Methods</option>
        <option value="cash"          {{ request('method')=='cash'          ? 'selected':'' }}>Cash</option>
        <option value="bank_transfer" {{ request('method')=='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
        <option value="cheque"        {{ request('method')=='cheque'        ? 'selected':'' }}>Cheque</option>
        <option value="online"        {{ request('method')=='online'        ? 'selected':'' }}>Online</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" style="height:40px;padding:0 18px;background:linear-gradient(135deg,#10b981,#34d399);color:white;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">Filter</button>
      <a href="{{ route('reports.payments') }}" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;">Clear</a>
    </div>
  </form>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Total Collected</div>
    <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:#10b981;">৳{{ number_format($total, 0) }}</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Transactions</div>
    <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:#6366f1;">{{ $count }}</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Average</div>
    <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:#f59e0b;">৳{{ number_format($average, 0) }}</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;">
    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Top Method</div>
    <div style="font-size:16px;font-weight:800;font-family:'Syne',sans-serif;color:#f43f5e;">{{ $topMethod }}</div>
  </div>
</div>

{{-- Table --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
  <div class="overflow-x-auto">
    <table class="w-full" style="border-collapse:collapse;">
      <thead>
        <tr style="background:var(--bg-surface-2);border-bottom:1px solid var(--border-color);">
          @foreach(['#','Receipt','Student','Fee Type','Method','Date','Amount'] as $h)
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:{{ $h=='Amount'?'right':'left' }};">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $i => $payment)
        @php
          $student = $payment->student;
          $sName = $student ? trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) : '—';
          $methodColors = ['cash'=>'#4ade80','bank_transfer'=>'#38bdf8','cheque'=>'#fbbf24','online'=>'#a78bfa'];
          $mc = $methodColors[$payment->payment_method ?? ''] ?? '#94a3b8';
        @endphp
        <tr style="border-bottom:1px solid var(--border-color);transition:background 0.15s;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='transparent'">
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ $i + 1 }}</td>
          <td style="padding:12px 16px;font-size:11px;color:var(--text-muted);font-family:monospace;">{{ $payment->receipt_number ?? '—' }}</td>
          <td style="padding:12px 16px;font-size:13px;font-weight:600;color:var(--text-primary);">{{ $sName }}</td>
          <td style="padding:12px 16px;font-size:13px;color:var(--text-secondary);">{{ $payment->fee_type ?? '—' }}</td>
          <td style="padding:12px 16px;">
            <span style="display:inline-block;padding:2px 9px;background:{{ $mc }}18;border:1px solid {{ $mc }}35;border-radius:6px;font-size:11px;font-weight:600;color:{{ $mc }};">
              {{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '—')) }}
            </span>
          </td>
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') }}</td>
          <td style="padding:12px 16px;font-size:14px;font-weight:700;color:#10b981;text-align:right;">৳{{ number_format($payment->amount, 0) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="padding:50px;text-align:center;color:var(--text-muted);font-size:13px;">No payments found for selected period</td></tr>
        @endforelse
      </tbody>
      @if($count)
      <tfoot>
        <tr style="background:var(--bg-surface-2);border-top:2px solid var(--border-color);">
          <td colspan="6" style="padding:12px 16px;font-size:13px;font-weight:700;color:var(--text-primary);">Total ({{ $count }} transactions)</td>
          <td style="padding:12px 16px;font-size:15px;font-weight:800;color:#10b981;text-align:right;font-family:'Syne',sans-serif;">৳{{ number_format($total, 0) }}</td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>

@endsection