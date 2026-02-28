@extends('layouts.app')
@section('page-title', 'Expenses')
@section('breadcrumb', 'Finance · Expenses')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Expenses</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Manage and approve institutional spending</p>
  </div>
  <a href="{{ route('expenses.create') }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--accent);border-radius:10px;font-size:13px;font-weight:600;color:white;text-decoration:none;transition:all 0.2s;"
     onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
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
            <tr style="background:var(--bg-surface-2);border-bottom:1px solid var(--border-color);">
              <th class="text-left" style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:500;">Details</th>
              <th class="text-left" style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:500;">Vendor</th>
              <th class="text-right" style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:500;">Amount</th>
              <th class="text-left" style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:500;">Date</th>
              <th class="text-left" style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:500;">Status</th>
              <th class="text-right" style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:500;">Actions</th>
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
            <tr style="border-bottom:1px solid var(--border-color);border-left:3px solid {{ $sc }};transition:background 0.15s;"
                onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='transparent'">
              <td style="padding:14px 16px;">
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:4px;">{{ $expense->title ?? 'Expense' }}</div>
                <span class="badge" style="background:transparent;border:1px solid {{ $cc }};color:{{ $cc }};">{{ $expense->category ?? 'General' }}</span>
              </td>
              <td style="padding:14px 16px;font-size:13px;color:var(--text-secondary);">{{ $expense->vendor_name ?? '—' }}</td>
              <td style="padding:14px 16px;text-align:right;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--text-primary);">৳{{ number_format($expense->amount ?? 0) }}</td>
              <td style="padding:14px 16px;font-size:12px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($expense->expense_date ?? $expense->created_at)->format('M j, Y') }}<br><span style="font-size:10px;">{{ \Carbon\Carbon::parse($expense->expense_date ?? $expense->created_at)->format('h:i A') }}</span></td>
              <td style="padding:14px 16px;">
                <span class="badge {{ ($expense->status??'pending')==='approved' ? 'badge-green' : (($expense->status??'')==='rejected' ? 'badge-red' : 'badge-gold') }}">
                  {{ ucfirst($expense->status ?? 'pending') }}
                </span>
              </td>
              <td style="padding:14px 16px;">
                <div class="flex items-center justify-end gap-1">
                  @if(($expense->status ?? 'pending') === 'pending')
                    <form method="POST" action="{{ route('expenses.approve', $expense) }}" style="display:inline;">
                      @csrf <input type="hidden" name="action" value="approve">
                      <button type="submit" title="Approve" style="width:30px;height:30px;border-radius:8px;border:none;background:rgba(44,110,73,0.1);color:var(--accent-green);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='var(--accent-green)';this.style.color='white'" onmouseout="this.style.background='rgba(44,110,73,0.1)';this.style.color='var(--accent-green)'"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></button>
                    </form>
                    <form method="POST" action="{{ route('expenses.approve', $expense) }}" style="display:inline;">
                      @csrf <input type="hidden" name="action" value="reject">
                      <button type="submit" title="Reject" style="width:30px;height:30px;border-radius:8px;border:none;background:rgba(212,80,30,0.1);color:var(--accent);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='var(--accent)';this.style.color='white'" onmouseout="this.style.background='rgba(212,80,30,0.1)';this.style.color='var(--accent)'"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    </form>
                  @endif
                  <a href="{{ route('expenses.show', $expense) }}" title="View"
                     style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;color:var(--text-muted);transition:all 0.2s;"
                     onmouseover="this.style.background='var(--bg-surface-2)';this.style.color='var(--text-primary)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-muted)'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
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
        <div style="width:32px;height:32px;border-radius:8px;background:white;color:var(--accent-gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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

  </div>

</div>

@endsection
