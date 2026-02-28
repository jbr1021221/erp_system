@extends('layouts.app')
@section('page-title', 'Financial Report')
@section('breadcrumb', 'Reports · Financial Summary')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">Financial Summary</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">Income vs expenses with monthly breakdown</p>
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

{{-- Year filter --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;padding:16px;margin-bottom:16px;">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div class="min-w-[140px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Year</label>
      <select name="year" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        @foreach($years as $y)
          <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
      </select>
    </div>
    <button type="submit" style="height:40px;padding:0 18px;background:linear-gradient(135deg,#7c3aed,#a78bfa);color:white;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">Apply</button>
  </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
  <div style="background:var(--bg-surface);border:1px solid rgba(74,222,128,0.2);border-radius:14px;padding:20px 22px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
      <div style="width:36px;height:36px;border-radius:10px;background:rgba(74,222,128,0.15);display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
      </div>
      <span style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Total Income</span>
    </div>
    <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:#4ade80;">৳{{ number_format($totalIncome, 0) }}</div>
    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">{{ $year }} total collected</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid rgba(248,113,113,0.2);border-radius:14px;padding:20px 22px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
      <div style="width:36px;height:36px;border-radius:10px;background:rgba(248,113,113,0.15);display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="#f87171" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
      </div>
      <span style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Total Expenses</span>
    </div>
    <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:#f87171;">৳{{ number_format($totalExpenses, 0) }}</div>
    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">{{ $year }} total spent</div>
  </div>
  <div style="background:var(--bg-surface);border:1px solid {{ $netBalance >= 0 ? 'rgba(99,102,241,0.2)' : 'rgba(251,146,60,0.2)' }};border-radius:14px;padding:20px 22px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
      <div style="width:36px;height:36px;border-radius:10px;background:{{ $netBalance >= 0 ? 'rgba(99,102,241,0.15)' : 'rgba(251,146,60,0.15)' }};display:flex;align-items:center;justify-content:center;">
        <svg width="16" height="16" fill="none" stroke="{{ $netBalance >= 0 ? '#818cf8' : '#fb923c' }}" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
      </div>
      <span style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Net Balance</span>
    </div>
    <div style="font-size:28px;font-weight:800;font-family:'Syne',sans-serif;color:{{ $netBalance >= 0 ? '#818cf8' : '#fb923c' }};">{{ $netBalance >= 0 ? '+' : '' }}৳{{ number_format($netBalance, 0) }}</div>
    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">{{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }} for {{ $year }}</div>
  </div>
</div>

{{-- CSS bar chart --}}
@php $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;padding:22px;margin-bottom:16px;">
  <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:4px;font-family:'Syne',sans-serif;">Monthly Breakdown — {{ $year }}</div>
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
    <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);"><span style="width:12px;height:12px;border-radius:3px;background:#4ade80;display:inline-block;"></span>Income</span>
    <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);"><span style="width:12px;height:12px;border-radius:3px;background:#f87171;display:inline-block;"></span>Expenses</span>
  </div>
  <div style="display:grid;grid-template-columns:repeat(12,1fr);gap:8px;align-items:end;height:140px;">
    @foreach($months as $mi => $month)
    @php
      $inc = $monthlyIncome->get($mi + 1, 0);
      $exp = $monthlyExpenses->get($mi + 1, 0);
      $ih  = $maxVal > 0 ? max(4, round($inc / $maxVal * 120)) : 4;
      $eh  = $maxVal > 0 ? max(4, round($exp / $maxVal * 120)) : 4;
    @endphp
    <div style="display:flex;flex-direction:column;align-items:center;gap:3px;height:100%;justify-content:flex-end;">
      <div style="display:flex;gap:2px;align-items:flex-end;width:100%;">
        <div style="flex:1;height:{{ $ih }}px;background:linear-gradient(to top,#4ade80,#86efac);border-radius:4px 4px 0 0;" title="Income: ৳{{ number_format($inc, 0) }}"></div>
        <div style="flex:1;height:{{ $eh }}px;background:linear-gradient(to top,#f87171,#fca5a5);border-radius:4px 4px 0 0;" title="Expense: ৳{{ number_format($exp, 0) }}"></div>
      </div>
      <div style="font-size:9px;color:var(--text-muted);text-align:center;">{{ $month }}</div>
    </div>
    @endforeach
  </div>
</div>

{{-- Monthly table --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
  <div class="overflow-x-auto">
    <table class="w-full" style="border-collapse:collapse;">
      <thead>
        <tr style="background:var(--bg-surface-2);border-bottom:1px solid var(--border-color);">
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:left;">Month</th>
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:right;">Income</th>
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:right;">Expenses</th>
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:right;">Net</th>
        </tr>
      </thead>
      <tbody>
        @foreach($months as $mi => $month)
        @php
          $inc = $monthlyIncome->get($mi + 1, 0);
          $exp = $monthlyExpenses->get($mi + 1, 0);
          $net = $inc - $exp;
          $isCurrent = ($mi + 1) == date('n') && $year == date('Y');
        @endphp
        <tr style="border-bottom:1px solid var(--border-color);transition:background 0.15s;{{ $isCurrent ? 'background:rgba(99,102,241,0.04);' : '' }}"
            onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='{{ $isCurrent ? 'rgba(99,102,241,0.04)' : 'transparent' }}'">
          <td style="padding:12px 16px;font-size:13px;font-weight:{{ $isCurrent ? '700' : '500' }};color:var(--text-primary);">
            {{ $month }} {{ $year }}
            @if($isCurrent)
              <span style="margin-left:8px;font-size:10px;padding:1px 7px;background:rgba(99,102,241,0.15);border-radius:99px;color:#818cf8;font-weight:600;">Current</span>
            @endif
          </td>
          <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#4ade80;text-align:right;">{{ $inc > 0 ? '৳'.number_format($inc,0) : '—' }}</td>
          <td style="padding:12px 16px;font-size:13px;font-weight:600;color:#f87171;text-align:right;">{{ $exp > 0 ? '৳'.number_format($exp,0) : '—' }}</td>
          <td style="padding:12px 16px;font-size:13px;font-weight:700;color:{{ $net >= 0 ? '#818cf8' : '#fb923c' }};text-align:right;">
            {{ ($inc > 0 || $exp > 0) ? ($net >= 0 ? '+' : '').'৳'.number_format($net,0) : '—' }}
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="background:var(--bg-surface-2);border-top:2px solid var(--border-color);">
          <td style="padding:13px 16px;font-size:13px;font-weight:700;color:var(--text-primary);">Total {{ $year }}</td>
          <td style="padding:13px 16px;font-size:14px;font-weight:800;color:#4ade80;text-align:right;font-family:'Syne',sans-serif;">৳{{ number_format($totalIncome,0) }}</td>
          <td style="padding:13px 16px;font-size:14px;font-weight:800;color:#f87171;text-align:right;font-family:'Syne',sans-serif;">৳{{ number_format($totalExpenses,0) }}</td>
          <td style="padding:13px 16px;font-size:14px;font-weight:800;color:{{ $netBalance >= 0 ? '#818cf8' : '#fb923c' }};text-align:right;font-family:'Syne',sans-serif;">{{ $netBalance >= 0 ? '+' : '' }}৳{{ number_format($netBalance,0) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

@endsection