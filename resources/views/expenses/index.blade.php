@extends('layouts.app')
@section('page-title', 'Expenses')
@section('breadcrumb', 'Finance · Expenses')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
  <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Expenses</a>
@endsection


@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Expenses</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Manage and approve institutional spending</p>
  </div>
  <a href="{{ route('expenses.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors flex items-center gap-1.5 shadow-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Expense
  </a>
</div>

{{-- Main Layout: 65% left, 35% right --}}
<div class="flex flex-col lg:flex-row gap-6 items-start">

  {{-- Left Content --}}
  <div class="flex-1 w-full min-w-0 flex flex-col gap-4">

    {{-- Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide animate-in delay-1">
      @php $currentStatus = request('status', 'all'); @endphp
      @foreach(['all' => 'All Expenses', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $label)
      <a href="{{ route('expenses.index', ['status' => $val == 'all' ? null : $val]) }}"
         style="padding:8px 16px;border-radius:99px;font-size:13px;font-weight:600;white-space:nowrap;transition:all 0.2s;text-decoration:none;
                {{ $currentStatus === $val ? 'background:var(--accent);color:white;' : 'background:var(--bg-surface);color:var(--text-secondary);border:1px solid var(--border-color);' }}">
        {{ $label }}
      </a>
      @endforeach
    </div>

    {{-- Table Card --}}
    <div class="card overflow-hidden animate-in delay-2">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 font-medium border-b border-slate-200">
              <th class="text-left px-4 py-3">Details</th>
              <th class="text-left px-4 py-3">Vendor</th>
              <th class="text-right px-4 py-3">Amount</th>
              <th class="text-left px-4 py-3">Date</th>
              <th class="text-left px-4 py-3">Status</th>
              <th class="text-right px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($expenses ?? [] as $expense)
            @php
              $statusColors = ['pending'=>'var(--accent-gold)','approved'=>'var(--accent-green)','rejected'=>'var(--accent)'];
              $sc = $statusColors[$expense->status ?? 'pending'] ?? 'var(--text-muted)';
              $catColors = ['Utilities'=>'var(--accent-info)','Salaries'=>'var(--accent-green)','Supplies'=>'var(--accent-gold)','Maintenance'=>'var(--accent)','Transport'=>'var(--accent-info)'];
              $cc = $catColors[$expense->category ?? 'Utilities'] ?? 'var(--text-muted)';
            @endphp
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors" style="border-left:3px solid {{ $sc }};">
              <td class="px-4 py-3.5">
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:4px;">{{ $expense->title ?? 'Expense' }}</div>
                <span class="badge" style="background:transparent;border:1px solid {{ $cc }};color:{{ $cc }};">{{ $expense->category ?? 'General' }}</span>
              </td>
              <td class="px-4 py-3.5 text-slate-500 font-medium text-sm">{{ $expense->vendor_name ?? '—' }}</td>
              <td class="px-4 py-3.5 text-right font-medium text-slate-900">৳{{ number_format($expense->amount ?? 0) }}</td>
              <td class="px-4 py-3.5 text-slate-500 text-xs">{{ \Carbon\Carbon::parse($expense->expense_date ?? $expense->created_at)->format('M j, Y') }}<br><span style="font-size:10px;">{{ \Carbon\Carbon::parse($expense->expense_date ?? $expense->created_at)->format('h:i A') }}</span></td>
              <td class="px-4 py-3.5">
                <span class="badge {{ ($expense->status??'pending')==='approved' ? 'badge-green' : (($expense->status??'')==='rejected' ? 'badge-red' : 'badge-gold') }}">
                  {{ ucfirst($expense->status ?? 'pending') }}
                </span>
              </td>
              <td class="px-4 py-3.5">
                <div class="flex items-center justify-end gap-1">
                  @if(($expense->status ?? 'pending') === 'pending')
                    <form method="POST" action="{{ route('expenses.approve', $expense) }}" style="display:inline;">
                      @csrf <input type="hidden" name="action" value="approve">
                      <button type="submit" title="Approve" style="width:30px;height:30px;border-radius:8px;border:none;background:rgba(44,110,73,0.1);color:var(--accent-green);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='var(--accent-green)';this.style.color='white'" onmouseout="this.style.background='rgba(44,110,73,0.1)';this.style.color='var(--accent-green)'"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></button>
                    </form>
                    <form method="POST" action="{{ route('expenses.approve', $expense) }}" style="display:inline;">
                      @csrf <input type="hidden" name="action" value="reject">
                      <button type="submit" title="Reject" class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white" style="width:30px;height:30px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s;"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    </form>
                  @endif
                  <a href="{{ route('expenses.show', $expense) }}" title="View"
                     class="text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-md p-1.5 transition">
                    <svg width="15" height="15" fill="none" class="stroke-current" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:60px;text-align:center;color:var(--text-muted);font-size:13px;">No expenses found</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if(method_exists($expenses ?? collect(), 'hasPages') && $expenses->hasPages())
        <div class="px-5 py-4" style="border-top:1px solid var(--border-color);">{{ $expenses->links() }}</div>
      @endif
    </div>
  </div>

  {{-- Right Sidebar --}}
  <div class="w-full lg:w-[320px] xl:w-[350px] shrink-0 flex flex-col gap-4 sticky top-20 animate-in delay-3">

    {{-- Alert --}}
    @if(($pendingExpensesCount ?? 0) > 0)
    <div class="p-4 rounded-xl" style="background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);">
      <div class="flex items-start gap-3">
        <div class="text-slate-300 flex-shrink-0">
          <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
          <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--accent-gold);">Pending Approvals</h3>
          <p style="font-size:12px;color:var(--text-secondary);margin-top:2px;line-height:1.4;">{{ $pendingExpensesCount }} expenses require your approval to process.</p>
        </div>
      </div>
    </div>
    @endif

    {{-- Summary --}}
    <div class="card p-6">
      <h3 style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">This Month's Spending</h3>
      <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:32px;color:var(--text-primary);letter-spacing:-1px;">৳{{ number_format($monthSpending ?? 0) }}</div>

      <div style="height:1px;background:var(--border-color);margin:20px 0;"></div>

      <h3 style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">Category Breakdown</h3>
      <div class="flex flex-col gap-3">
        @php
          $cats = $categoryBreakdown ?? [
            ['name'=>'Salaries','amount'=>0,'color'=>'var(--accent-green)'],
            ['name'=>'Utilities','amount'=>0,'color'=>'var(--accent-info)'],
            ['name'=>'Maintenance','amount'=>0,'color'=>'var(--accent)'],
            ['name'=>'Supplies','amount'=>0,'color'=>'var(--accent-gold)'],
          ];
        @endphp
        @foreach($cats as $cat)
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div style="width:10px;height:10px;border-radius:3px;background:{{ $cat['color'] }};"></div>
            <span style="font-size:13px;color:var(--text-secondary);font-weight:500;">{{ $cat['name'] }}</span>
          </div>
          <span style="font-family:'Syne',sans-serif;font-weight:600;font-size:13px;color:var(--text-primary);">৳{{ number_format($cat['amount']) }}</span>
        </div>
        @endforeach
      </div>
    </div>

  @endsection
