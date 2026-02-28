@extends('layouts.app')
@section('page-title', 'Income & Fees')
@section('breadcrumb', 'Finance · Payments')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
  <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Expenses</a>
@endsection


@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Income & Fees</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Track all student payments and outstanding balances</p>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('fee-structures.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;font-weight:500;color:var(--text-secondary);text-decoration:none;transition:all 0.2s;"
       onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='var(--bg-surface)'">
      Fee Structure
    </a>
    <a href="{{ route('payments.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors flex items-center gap-1.5 shadow-sm">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Collect Fee
    </a>
  </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
  @php
    $summaryCards = [
      ['label'=>'Total Collected','value'=>'৳'.number_format($totalCollected ?? 0),'color'=>'var(--accent-green)','bg'=>'rgba(44,110,73,0.1)'],
      ['label'=>'This Month','value'=>'৳'.number_format($monthCollected ?? 0),'color'=>'var(--accent-info)','bg'=>'rgba(26,82,118,0.1)'],
      ['label'=>'Outstanding','value'=>'৳'.number_format($totalOutstanding ?? 0),'color'=>'var(--accent)','bg'=>'rgba(212,80,30,0.1)'],
      ['label'=>'Partial Payments','value'=>$partialCount ?? 0,'color'=>'var(--accent-gold)','bg'=>'rgba(201,168,76,0.1)'],
    ];
  @endphp
  @foreach($summaryCards as $i => $sc)
  <div class="animate-in delay-{{ $i+1 }} rounded-2xl p-4" style="background:{{ $sc['bg'] }};border:1px solid {{ $sc['bg'] }};">
    <div style="font-size:11px;font-weight:500;color:{{ $sc['color'] }};text-transform:uppercase;letter-spacing:0.5px;">{{ $sc['label'] }}</div>
    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:22px;color:{{ $sc['color'] }};margin-top:4px;">{{ $sc['value'] }}</div>
  </div>
  @endforeach
</div>

{{-- Filters --}}
<div class="card p-4 mb-4 animate-in delay-2">
  <form method="GET" action="{{ route('payments.index') }}" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[160px]">
      <label style="font-size:11px;font-weight:500;color:var(--text-muted);display:block;margin-bottom:4px;">SEARCH STUDENT</label>
      <div class="relative">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or ID..."
          style="width:100%;height:40px;padding:0 12px 0 34px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;"
          onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
      </div>
    </div>
    <div class="min-w-[130px]">
      <label style="font-size:11px;font-weight:500;color:var(--text-muted);display:block;margin-bottom:4px;">STATUS</label>
      <select name="status" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All</option>
        <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Paid</option>
        <option value="partial" {{ request('status')=='partial' ? 'selected' : '' }}>Partial</option>
        <option value="overdue" {{ request('status')=='overdue' ? 'selected' : '' }}>Overdue</option>
      </select>
    </div>
    <div class="min-w-[130px]">
      <label style="font-size:11px;font-weight:500;color:var(--text-muted);display:block;margin-bottom:4px;">CLASS</label>
      <select name="class_id" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Classes</option>
        @foreach($classes ?? [] as $class)
          <option value="{{ $class->id }}" {{ request('class_id')==$class->id ? 'selected' : '' }}>{{ $class->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="min-w-[120px]">
      <label style="font-size:11px;font-weight:500;color:var(--text-muted);display:block;margin-bottom:4px;">FROM DATE</label>
      <input type="date" name="from" value="{{ request('from') }}" style="width:100%;height:40px;padding:0 10px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
    </div>
    <div class="flex gap-2">
      <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors">Filter</button>
      <a href="{{ route('payments.index') }}" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;background:var(--bg-surface-2);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;">Clear</a>
    </div>
  </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden animate-in delay-3">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 font-medium">
          <th class="text-left px-4 py-3">Student</th>
          <th class="text-left px-4 py-3">Class</th>
          <th class="text-right px-4 py-3">Amount Due</th>
          <th class="text-right px-4 py-3">Paid</th>
          <th class="text-right px-4 py-3">Balance</th>
          <th class="text-left px-4 py-3">Date</th>
          <th class="text-left px-4 py-3">Status</th>
          <th class="text-right px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments ?? [] as $payment)
        @php
          $pname = $payment->student->name ?? 'Unknown';
          $pi = implode('', array_map(fn($p)=>strtoupper($p[0]), array_slice(explode(' ',$pname),0,2)));
          $statusColor = ['paid'=>'var(--accent-green)','partial'=>'var(--accent-gold)','overdue'=>'var(--accent)'][$payment->status ?? 'paid'] ?? 'var(--text-muted)';
          $balance = ($payment->amount_due ?? 0) - ($payment->amount_paid ?? 0);
        @endphp
        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors" style="border-left:3px solid {{ $statusColor }};">
          <td class="px-4 py-3.5">
            <div class="flex items-center gap-3">
              <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--accent),var(--accent-gold));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:white;flex-shrink:0;">{{ $pi }}</div>
              <div>
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">{{ $pname }}</div>
                <div style="font-size:11px;color:var(--text-muted);">{{ $payment->student->student_id ?? '' }}</div>
              </div>
            </div>
          </td>
          <td class="px-4 py-3.5 text-slate-500 font-medium text-sm">{{ $payment->student->schoolClass->name ?? '—' }}</td>
          <td class="px-4 py-3.5 text-right font-medium text-slate-900">৳{{ number_format($payment->amount_due ?? 0) }}</td>
          <td class="px-4 py-3.5 text-right font-medium text-emerald-600">৳{{ number_format($payment->amount_paid ?? 0) }}</td>
          <td class="px-4 py-3.5 text-right font-medium" style="color:{{ $balance > 0 ? 'var(--accent)' : 'var(--text-muted)' }};">৳{{ number_format($balance) }}</td>
          <td class="px-4 py-3.5 text-slate-500 text-xs">{{ \Carbon\Carbon::parse($payment->created_at)->format('M j, Y') }}</td>
          <td class="px-4 py-3.5">
            <span class="badge {{ $payment->status === 'paid' ? 'badge-green' : ($payment->status === 'partial' ? 'badge-gold' : 'badge-red') }}">
              {{ ucfirst($payment->status ?? 'paid') }}
            </span>
          </td>
          <td class="px-4 py-3.5">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('payments.show', $payment) }}" title="Receipt"
                 class="text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-md p-1.5 transition">
                <svg width="15" height="15" fill="none" class="stroke-current" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              </a>
              <a href="{{ route('payments.edit', $payment) }}" title="Edit"
                 class="text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md p-1.5 transition">
                <svg width="15" height="15" fill="none" class="stroke-current" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="padding:60px;text-align:center;color:var(--text-muted);font-size:13px;">No payment records found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if(method_exists($payments ?? collect(), 'hasPages') && ($payments->hasPages()))
  <div class="flex items-center justify-between px-5 py-4" style="border-top:1px solid var(--border-color);">
    <span style="font-size:12px;color:var(--text-muted);">Showing {{ $payments->firstItem() }}–{{ $payments->lastItem() }} of {{ $payments->total() }} records</span>
    {{ $payments->links() }}
  </div>
  @endif
</div>

@endsection
