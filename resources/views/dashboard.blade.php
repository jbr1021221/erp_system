@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Overview · Today')

@push('styles')
<style>
  /* ============================================
     PREMIUM DASHBOARD — GLASSMORPHISM + NEON
     Dark-mode-safe: explicit color on every text
  ============================================ */

  /* Background */
  .dash-bg {
    position: fixed; inset: 0; z-index: -1; pointer-events: none;
    background: var(--bg-base);
  }

  /* ── HERO BANNER ── */
  .hero-banner {
    position: relative; overflow: hidden;
    border-radius: 8px;
    padding: 24px 32px;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
  }
  .hero-date {
    font-size: 13px; font-weight: 500;
    color: var(--text-muted);
  }
  .hero-greeting {
    font-family: 'Outfit', sans-serif; font-weight: 700;
    font-size: 24px; color: var(--text-primary); margin-top: 4px;
  }
  .hero-sub { font-size: 14px; color: var(--text-secondary); margin-top: 4px; }
  .hero-btn-ghost {
    padding: 8px 16px; border-radius: 6px;
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-primary); font-size: 13px; font-weight: 500;
    text-decoration: none; transition: all 0.2s; white-space: nowrap;
  }
  .hero-btn-ghost:hover { background: var(--bg-surface-2); }
  .hero-btn-solid {
    padding: 8px 16px; border-radius: 6px;
    background: var(--accent); color: #ffffff;
    font-size: 13px; font-weight: 500;
    text-decoration: none; white-space: nowrap;
    transition: all 0.2s;
  }
  .hero-btn-solid:hover { background: var(--accent-hover); box-shadow: var(--shadow-sm); }

  /* ── STAT CARDS ── */
  .stat-card {
    position: relative; overflow: hidden;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 24px;
    transition: box-shadow 0.2s;
    box-shadow: none;
  }
  .stat-card:hover {
    box-shadow: var(--shadow-sm);
  }
  .stat-label {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px; font-weight: 500;
    color: var(--text-muted);
  }
  .stat-value {
    font-family: 'Outfit', sans-serif; font-weight: 700;
    line-height: 1; margin-top: 12px;
    color: var(--text-primary) !important;
  }
  .stat-value.lg { font-size: 28px; }
  .stat-value.md { font-size: 24px; }
  .stat-icon {
    position: absolute;
    top: 24px;
    right: 24px;
    opacity: 0.4;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .stat-icon svg {
    width: 20px;
    height: 20px;
    stroke: var(--accent);
  }

  /* DARK MODE explicit text fix */
  .dark .stat-value { color: #F5F0E8 !important; }
  .dark .stat-label { color: #7A7068 !important; }
  .dark .insight-value { color: #F5F0E8 !important; }
  .dark .insight-label { color: #7A7068 !important; }
  .dark .card-title { color: #F5F0E8 !important; }
  .dark .card-muted { color: #7A7068 !important; }
  .dark .table-cell { color: #BDB5AB !important; }
  .dark .table-cell-primary { color: #F5F0E8 !important; }
  .dark .summary-value { color: #F5F0E8 !important; }

  /* ── INSIGHT MINI CARDS ── */
  .insight-card {
    display: flex; align-items: center; gap: 14px;
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 8px; padding: 16px 18px;
    transition: box-shadow 0.2s; box-shadow: none;
    position: relative; overflow: hidden;
  }
  .insight-card:hover { box-shadow: var(--shadow-sm); }
  .insight-value {
    font-family: 'Outfit', sans-serif; font-weight: 700;
    font-size: 20px; color: var(--text-primary);
  }
  .insight-label {
    font-size: 13px; font-weight: 500; color: var(--text-muted); line-height: 1.2; margin-top: 4px;
  }
  .insight-icon {
    width: 40px; height: 40px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }

  /* ── CHART CARD ── */
  .chart-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 8px; overflow: hidden;
    box-shadow: none; transition: box-shadow 0.2s;
  }
  .chart-card:hover { box-shadow: var(--shadow-sm); }
  .card-title {
    font-family: 'Outfit', sans-serif; font-weight: 600;
    font-size: 15px; color: var(--text-primary);
  }
  .card-muted { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
  .chart-period-pill {
    display: flex; gap: 2px;
    background: var(--bg-surface-2);
    border-radius: 12px; padding: 4px;
  }
  .period-btn {
    padding: 5px 14px; border-radius: 9px;
    font-size: 11px; font-weight: 600; border: none; cursor: pointer;
    transition: all 0.2s; color: var(--text-muted); background: transparent;
  }
  .period-btn.active {
    background: var(--accent); color: white;
    box-shadow: 0 2px 10px rgba(212,80,30,0.35);
  }

  /* ── BY CLASS ROWS ── */
  .class-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px; border-radius: 14px;
    background: var(--bg-surface-2);
    transition: all 0.15s; cursor: pointer;
  }
  .class-row:hover { background: var(--bg-base); transform: translateX(4px); }
  .class-rank {
    width: 28px; height: 28px; border-radius: 8px;
    background: var(--text-primary); color: var(--bg-base);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 800; font-size: 10px;
    flex-shrink: 0;
  }
  .class-progress-track {
    flex: 1; height: 4px; border-radius: 99px;
    background: rgba(0,0,0,0.06); overflow: hidden;
  }
  .dark .class-progress-track { background: rgba(255,255,255,0.06); }
  .class-count {
    font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px;
    color: var(--text-muted); flex-shrink: 0;
    white-space: nowrap;
  }

  /* ── PAYMENT STATUS ── */
  .pstat-bar-track {
    height: 6px; border-radius: 99px;
    background: var(--bg-surface-2); overflow: hidden; margin-top: 8px;
  }

  /* ── ALERTS ── */
  .alert-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px; border-radius: 6px; border-left: 3px solid;
  }
  .alert-title { font-size: 13px; font-weight: 600; }
  .alert-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

  /* ── RECENT TABLE ── */
  .recent-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-color);
    border-radius: 8px; overflow: hidden;
    box-shadow: none; transition: box-shadow 0.2s;
  }
  .recent-card:hover { box-shadow: var(--shadow-sm); }
  .table-cell { font-size: 14px; color: var(--text-secondary); }
  .table-cell-primary { font-size: 14px; font-weight: 500; color: var(--text-primary); }
  .summary-value {
    font-family: 'Outfit', sans-serif; font-weight: 600;
    font-size: 15px; color: var(--text-primary);
  }

  /* Avatar initials */
  .avatar {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 800; font-size: 11px;
    color: white; flex-shrink: 0;
  }

  /* Animated number counter text fix */
  [data-countup] {
    display: inline; color: inherit !important;
  }

  /* Fade-up animation */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .au  { animation: fadeUp 0.4s ease both; }
  .d1  { animation-delay: 0.04s; } .d2 { animation-delay: 0.08s; }
  .d3  { animation-delay: 0.12s; } .d4 { animation-delay: 0.16s; }
  .d5  { animation-delay: 0.20s; } .d6 { animation-delay: 0.24s; }
  .d7  { animation-delay: 0.28s; } .d8 { animation-delay: 0.32s; }

  /* Progress bar animation */
  @keyframes growWidth { from { width: 0; } }
  .pbar { border-radius: 99px; animation: growWidth 0.9s ease both; animation-delay: 0.5s; }

  /* Scrollbar */
  .slim-scroll::-webkit-scrollbar { width: 4px; }
  .slim-scroll::-webkit-scrollbar-track { background: transparent; }
  .slim-scroll::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 99px; }
</style>
@endpush

@section('content')
<div class="dash-bg"></div>

{{-- ═══════════════════════════════════════════════
     HERO BANNER
═══════════════════════════════════════════════ --}}
<div class="hero-banner au d1 mb-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 relative z-10">
    <div>
      <div class="hero-date">{{ now()->format('l, F j · Y') }}</div>
      <div class="hero-greeting">
        {{ now()->hour < 12 ? '☀️ Good Morning' : (now()->hour < 17 ? '🌤 Good Afternoon' : '🌙 Good Evening') }}, {{ auth()->user()->name ?? 'Admin' }}
      </div>
      <div class="hero-sub">Academic Year {{ date('Y') }}–{{ date('Y')+1 }} &nbsp;·&nbsp; EduERP Admin Suite</div>
    </div>
    <div class="flex gap-3 flex-shrink-0">
      <a href="{{ route('students.create') }}" class="hero-btn-ghost">+ Add Student</a>
      <a href="{{ route('payments.create') }}" class="hero-btn-solid">💳 Collect Fee</a>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     STAT CARDS — 4 columns
═══════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">

  {{-- Total Students --}}
  <div class="stat-card blue au d1">
    <div class="flex items-start justify-between mb-4">
      <div>
        <div class="stat-label">Total Students</div>
        <div class="stat-value lg" data-countup data-target="{{ $totalStudents ?? 0 }}">{{ $totalStudents ?? 0 }}</div>
      </div>
      <div class="stat-icon">
        <svg fill="none" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
    </div>
    <div class="flex items-center gap-2 mt-2">
      <span class="badge badge-green">{{ $activeStudents ?? 0 }} Active</span>
      <span style="font-size:11px;color:var(--text-muted);">{{ $inactiveStudents ?? 0 }} Inactive</span>
    </div>
  </div>

  {{-- Total Revenue --}}
  <div class="stat-card green au d2">
    <div class="flex items-start justify-between mb-4">
      <div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value md">৳<span data-countup data-target="{{ $totalRevenue ?? 0 }}">{{ number_format($totalRevenue ?? 0) }}</span></div>
      </div>
      <div class="stat-icon">
        <svg fill="none" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:#2C6E49;font-weight:600;">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
      +৳{{ number_format($monthRevenue ?? 0) }} this month
    </div>
  </div>

  {{-- Today's Collection --}}
  <div class="stat-card gold au d3">
    <div class="flex items-start justify-between mb-4">
      <div>
        <div class="stat-label">Today's Collection</div>
        <div class="stat-value lg">৳<span data-countup data-target="{{ $todayCollection ?? 0 }}">{{ number_format($todayCollection ?? 0) }}</span></div>
      </div>
      <div class="stat-icon">
        <svg fill="none" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8L2 7h20z"/></svg>
      </div>
    </div>
    <div style="font-size:12px;color:var(--text-muted);" class="card-muted">Collected today from payments</div>
  </div>

  {{-- Outstanding Fees --}}
  <div class="stat-card red au d4">
    <div class="flex items-start justify-between mb-4">
      <div>
        <div class="stat-label">Outstanding Fees</div>
        <div class="stat-value md">৳<span data-countup data-target="{{ $outstandingFees ?? 0 }}">{{ number_format($outstandingFees ?? 0) }}</span></div>
      </div>
      <div class="stat-icon">
        <svg fill="none" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
    </div>
    <a href="{{ route('payments.index', ['status' => 'pending']) }}" style="font-size:12px;font-weight:600;color:var(--accent);text-decoration:none;">
      {{ $pendingExpenses ?? 0 }} pending expenses →
    </a>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     QUICK INSIGHTS STRIP
═══════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
  @php
    $insights = [
      ['label'=>'New This Month',   'value'=>$newStudentsMonth ?? 0,      'color'=>'#1A5276', 'bg'=>'rgba(26,82,118,0.1)',   'icon'=>'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
      ['label'=>'Collection Rate',  'value'=>($collectionRate ?? 0).'%',  'color'=>'#2C6E49', 'bg'=>'rgba(44,110,73,0.1)',   'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
      ['label'=>'Active Teachers',  'value'=>$activeTeachers ?? 0,        'color'=>'#C9A84C', 'bg'=>'rgba(201,168,76,0.1)',  'icon'=>'M12 14l9-5-9-5-9 5 9 5zm0 7v-7'],
      ['label'=>'Pending Expenses', 'value'=>$pendingExpenses ?? 0,       'color'=>'#D4501E', 'bg'=>'rgba(212,80,30,0.1)',   'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ];
  @endphp
  @foreach($insights as $i => $ins)
  <div class="insight-card au d{{ $i+1 }}">
    <div class="insight-icon" style="background:{{ $ins['bg'] }};">
      <svg width="20" height="20" fill="none" stroke="{{ $ins['color'] }}" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $ins['icon'] }}"/></svg>
    </div>
    <div>
      <div class="insight-label">{{ $ins['label'] }}</div>
      <div class="insight-value" style="color:var(--text-primary);">{{ $ins['value'] }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- ═══════════════════════════════════════════════
     REVENUE CHART  +  BY CLASS
═══════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-5">

  {{-- Chart --}}
  <div class="chart-card lg:col-span-3 au d5">
    <div class="p-6">
      <div class="flex items-start justify-between mb-5">
        <div>
          <div class="card-title">Revenue Trend</div>
          <div class="card-muted">Monthly income overview</div>
        </div>
        <div class="chart-period-pill">
          @foreach(['3M' => 3, '6M' => 6, '1Y' => 12] as $label => $months)
            <button onclick="loadChart({{ $months }}, this)"
              class="period-btn {{ $months === 6 ? 'active' : '' }}">{{ $label }}</button>
          @endforeach
        </div>
      </div>
      <div style="position:relative;height:200px;">
        <canvas id="revenueChart"></canvas>
      </div>
      <div class="grid grid-cols-3 gap-3 mt-5 pt-4" style="border-top:1px solid var(--border-color);">
        @php
          $chartSummary = [
            ['label'=>'Monthly Avg',  'val'=>'৳'.number_format($avgMonthlyRevenue ?? 0),  'col'=>'var(--text-primary)'],
            ['label'=>'Best Month',   'val'=>'৳'.number_format($bestMonthRevenue ?? 0),   'col'=>'#2C6E49'],
            ['label'=>'Growth Rate',  'val'=>($growthRate ?? 0).'%',                       'col'=>($growthRate ?? 0) >= 0 ? '#2C6E49' : '#D4501E'],
          ];
        @endphp
        @foreach($chartSummary as $s)
        <div class="text-center">
          <div style="font-size:10px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.8px;" class="card-muted">{{ $s['label'] }}</div>
          <div class="summary-value" style="font-size:17px;margin-top:5px;color:{{ $s['col'] }};">{{ $s['val'] }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- By Class --}}
  <div class="chart-card lg:col-span-2 au d5">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <div class="card-title">By Class</div>
        <a href="{{ route('classes.index') }}" style="font-size:12px;color:var(--accent);font-weight:600;text-decoration:none;">View All →</a>
      </div>
      <div class="flex flex-col gap-2 slim-scroll" style="max-height:256px;overflow-y:auto;">
        @php
          $clrPalette = ['#D4501E','#2C6E49','#C9A84C','#1A5276','#C0392B'];
          $maxStudents = collect($classSummary ?? [])->max('student_count') ?: 1;
        @endphp
        @forelse($classSummary ?? [] as $i => $class)
        <div class="class-row">
          <div class="class-rank">{{ $i+1 }}</div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:13px;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" class="table-cell-primary">{{ $class->name }}</div>
            <div class="class-progress-track" style="margin-top:5px;">
              <div class="pbar" style="height:4px;width:{{ ($class->student_count / $maxStudents) * 100 }}%;background:{{ $clrPalette[$i % count($clrPalette)] }};"></div>
            </div>
          </div>
          <div class="class-count">{{ $class->student_count }}</div>
        </div>
        @empty
        <p style="text-align:center;padding:24px 0;font-size:13px;color:var(--text-muted);">No classes found</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     EXPENSE DONUT  +  PAYMENT STATUS  +  ALERTS
═══════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-5">

  {{-- Expense Breakdown --}}
  <div class="chart-card p-6 au d1">
    <div class="flex items-center justify-between mb-4">
      <div class="card-title">Expense Breakdown</div>
      <span class="badge badge-muted">This Month</span>
    </div>
    <div style="position:relative;height:160px;display:flex;align-items:center;justify-content:center;">
      <canvas id="expenseDonut"></canvas>
    </div>
    <div class="flex flex-col gap-2.5 mt-4">
      @php
        $expenseCategories = $expenseSummary ?? [
          ['name'=>'Utilities',   'amount'=>0, 'color'=>'#1A5276'],
          ['name'=>'Salaries',    'amount'=>0, 'color'=>'#2C6E49'],
          ['name'=>'Supplies',    'amount'=>0, 'color'=>'#C9A84C'],
          ['name'=>'Maintenance', 'amount'=>0, 'color'=>'#D4501E'],
        ];
      @endphp
      @foreach($expenseCategories as $cat)
      <div style="display:flex;align-items:center;justify-content:between;">
        <div style="display:flex;align-items:center;gap:8px;flex:1;">
          <div style="width:8px;height:8px;border-radius:3px;background:{{ $cat['color'] }};flex-shrink:0;"></div>
          <span style="font-size:12px;color:var(--text-secondary);" class="table-cell">{{ $cat['name'] }}</span>
        </div>
        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--text-primary);margin-left:auto;" class="summary-value">৳{{ number_format($cat['amount']) }}</span>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Payment Status --}}
  <div class="chart-card p-6 au d2">
    <div class="flex items-center justify-between mb-5">
      <div class="card-title">Payment Status</div>
      <a href="{{ route('payments.index') }}" style="font-size:11px;color:var(--accent);font-weight:600;text-decoration:none;">Details →</a>
    </div>
    @php
      $payStats = [
        ['label'=>'Paid',    'val'=>$paidCount ?? 0,    'color'=>'#2C6E49', 'bg'=>'rgba(44,110,73,0.1)'],
        ['label'=>'Partial', 'val'=>$partialCount ?? 0, 'color'=>'#C9A84C', 'bg'=>'rgba(201,168,76,0.1)'],
        ['label'=>'Overdue', 'val'=>$overdueCount ?? 0, 'color'=>'#D4501E', 'bg'=>'rgba(212,80,30,0.1)'],
      ];
      $totalPay = max(array_sum(array_column($payStats,'val')), 1);
    @endphp
    <div class="flex flex-col gap-4">
      @foreach($payStats as $ps)
      <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:8px;height:8px;border-radius:50%;background:{{ $ps['color'] }};"></div>
            <span style="font-size:13px;font-weight:500;color:var(--text-secondary);" class="table-cell">{{ $ps['label'] }}</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:11px;color:var(--text-muted);">{{ round(($ps['val']/$totalPay)*100) }}%</span>
            <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:16px;color:var(--text-primary);" class="summary-value">{{ $ps['val'] }}</span>
          </div>
        </div>
        <div class="pstat-bar-track">
          <div class="pbar" style="height:6px;width:{{ round(($ps['val']/$totalPay)*100) }}%;background:{{ $ps['color'] }};"></div>
        </div>
      </div>
      @endforeach
    </div>
    <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border-color);display:flex;align-items:center;justify-content:space-between;">
      <span style="font-size:12px;color:var(--text-muted);">Total Payments</span>
      <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);" class="summary-value">{{ $totalPay }}</span>
    </div>
  </div>

  {{-- Alerts --}}
  <div class="chart-card p-6 au d3">
    <div class="flex items-center justify-between mb-4">
      <div class="card-title">Alerts & Reminders</div>
      <span class="badge badge-muted">Today</span>
    </div>
    <div class="flex flex-col gap-3">
      @if(($overdueCount ?? 0) > 0)
      <div class="alert-item" style="background:rgba(212,80,30,0.06);border-color:#D4501E;">
        <svg width="16" height="16" fill="none" stroke="#D4501E" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
          <div class="alert-title" style="color:#D4501E;">{{ $overdueCount }} Overdue Payments</div>
          <div class="alert-sub">Requires immediate follow-up</div>
        </div>
      </div>
      @endif
      @if(($pendingExpenses ?? 0) > 0)
      <div class="alert-item" style="background:rgba(201,168,76,0.06);border-color:#C9A84C;">
        <svg width="16" height="16" fill="none" stroke="#C9A84C" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div>
          <div class="alert-title" style="color:#C9A84C;">{{ $pendingExpenses }} Expense Approvals</div>
          <div class="alert-sub">Pending your review</div>
        </div>
      </div>
      @endif
      <div class="alert-item" style="background:rgba(44,110,73,0.06);border-color:#2C6E49;">
        <svg width="16" height="16" fill="none" stroke="#2C6E49" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <div>
          <div class="alert-title" style="color:#2C6E49;">System Running Normally</div>
          <div class="alert-sub">All services operational</div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     RECENT ADMISSIONS  +  RECENT PAYMENTS
═══════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 au d7">

  {{-- Recent Admissions --}}
  <div class="recent-card overflow-hidden">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--border-color);">
      <div class="card-title">Recent Admissions</div>
      <a href="{{ route('students.index') }}"
         style="font-size:11px;color:var(--accent);font-weight:600;text-decoration:none;padding:5px 14px;background:rgba(212,80,30,0.08);border-radius:8px;transition:all 0.2s;"
         onmouseover="this.style.background='rgba(212,80,30,0.16)'"
         onmouseout="this.style.background='rgba(212,80,30,0.08)'">View All →</a>
    </div>
    <div class="overflow-x-auto">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--bg-surface-2);">
            <th style="text-align:left;padding:10px 20px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Student</th>
            <th style="text-align:left;padding:10px 14px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Class</th>
            <th style="text-align:left;padding:10px 14px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Date</th>
            <th style="text-align:left;padding:10px 14px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentAdmissions ?? [] as $student)
          @php
            $initials = implode('', array_map(fn($p)=>strtoupper($p[0]), array_slice(explode(' ',$student->name??'?'),0,2)));
            $gi = (ord($student->name[0]??'A')-65)%8;
            $avGrads=[['#D4501E','#C9A84C'],['#2C6E49','#C9A84C'],['#1A5276','#2C6E49'],['#8B4513','#D4501E'],['#1A5276','#D4501E'],['#2C6E49','#1A5276'],['#C9A84C','#D4501E'],['#6B3A2A','#C9A84C']];
            $ag=$avGrads[$gi];
          @endphp
          <tr style="border-bottom:1px solid var(--border-color);transition:background 0.15s;"
              onmouseover="this.style.background='var(--bg-surface-2)'"
              onmouseout="this.style.background='transparent'">
            <td style="padding:13px 20px;">
              <div style="display:flex;align-items:center;gap:12px;">
                <div class="avatar" style="background:linear-gradient(135deg,{{ $ag[0] }},{{ $ag[1] }});">{{ $initials }}</div>
                <div>
                  <div class="table-cell-primary">{{ $student->name }}</div>
                  <div style="font-size:11px;color:var(--text-muted);">{{ $student->student_id ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td style="padding:13px 14px;" class="table-cell">{{ $student->class->name ?? '—' }}</td>
            <td style="padding:13px 14px;font-size:12px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($student->created_at)->format('M j, Y') }}</td>
            <td style="padding:13px 14px;">
              <span class="badge {{ $student->status === 'active' ? 'badge-green' : ($student->status === 'transferred' ? 'badge-muted' : 'badge-gold') }}">
                {{ ucfirst($student->status ?? 'active') }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="padding:40px;text-align:center;font-size:13px;color:var(--text-muted);">No recent admissions</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Recent Payments --}}
  <div class="recent-card overflow-hidden">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid var(--border-color);">
      <div class="card-title">Recent Payments</div>
      <a href="{{ route('payments.index') }}"
         style="font-size:11px;color:#2C6E49;font-weight:600;text-decoration:none;padding:5px 14px;background:rgba(44,110,73,0.08);border-radius:8px;transition:all 0.2s;"
         onmouseover="this.style.background='rgba(44,110,73,0.16)'"
         onmouseout="this.style.background='rgba(44,110,73,0.08)'">View All →</a>
    </div>
    <div class="overflow-x-auto">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--bg-surface-2);">
            <th style="text-align:left;padding:10px 20px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Student</th>
            <th style="text-align:left;padding:10px 14px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Amount</th>
            <th style="text-align:left;padding:10px 14px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Date</th>
            <th style="text-align:left;padding:10px 14px;font-size:10px;text-transform:uppercase;letter-spacing:1.2px;color:var(--text-muted);font-weight:700;">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentPayments ?? [] as $payment)
          @php
            $pname = $payment->student->name ?? 'Unknown';
            $pi = implode('', array_map(fn($p)=>strtoupper($p[0]), array_slice(explode(' ',$pname),0,2)));
            $pgi = (ord($pname[0]??'A')-65)%8;
            $pg  = $avGrads[$pgi];
            $sColor = $payment->status==='paid' ? '#2C6E49' : ($payment->status==='partial' ? '#C9A84C' : '#D4501E');
          @endphp
          <tr style="border-bottom:1px solid var(--border-color);border-left:3px solid {{ $sColor }};transition:background 0.15s;"
              onmouseover="this.style.background='var(--bg-surface-2)'"
              onmouseout="this.style.background='transparent'">
            <td style="padding:13px 20px;">
              <div style="display:flex;align-items:center;gap:12px;">
                <div class="avatar" style="background:linear-gradient(135deg,{{ $pg[0] }},{{ $pg[1] }});">{{ $pi }}</div>
                <span class="table-cell-primary">{{ $pname }}</span>
              </div>
            </td>
            <td style="padding:13px 14px;">
              <span class="summary-value" style="font-size:15px;">৳{{ number_format($payment->amount ?? 0) }}</span>
            </td>
            <td style="padding:13px 14px;font-size:12px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($payment->created_at)->format('M j') }}</td>
            <td style="padding:13px 14px;">
              <span class="badge {{ $payment->status==='paid' ? 'badge-green' : ($payment->status==='partial' ? 'badge-gold' : 'badge-red') }}">
                {{ ucfirst($payment->status ?? 'paid') }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="padding:40px;text-align:center;font-size:13px;color:var(--text-muted);">No recent payments</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
/* ── Revenue Chart ── */
let revenueChart;
function buildChart(labels, data) {
  const ctx = document.getElementById('revenueChart')?.getContext('2d');
  if (!ctx) return;
  const isDark = document.documentElement.classList.contains('dark');
  if (revenueChart) revenueChart.destroy();
  const grad = ctx.createLinearGradient(0, 0, 0, 200);
  grad.addColorStop(0, 'rgba(212,80,30,0.22)');
  grad.addColorStop(1, 'rgba(212,80,30,0.00)');
  const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.04)';
  const tickColor = isDark ? '#7A7068' : '#9A8F85';
  const bgColor   = isDark ? '#1E1812' : '#fff';
  const txtColor  = isDark ? '#F5F0E8' : '#1A1614';
  revenueChart = new Chart(ctx, {
    type: 'line',
    data: { labels, datasets:[{
      data, borderColor:'#D4501E', borderWidth:2.5,
      backgroundColor:grad, fill:true, tension:0.42,
      pointBackgroundColor:'#D4501E',
      pointBorderColor: isDark ? '#1E1812' : '#FDFAF5',
      pointBorderWidth:3, pointRadius:5, pointHoverRadius:7,
    }]},
    options: {
      responsive:true, maintainAspectRatio:false,
      plugins: {
        legend:{ display:false },
        tooltip:{
          backgroundColor:bgColor, titleColor:txtColor, bodyColor:tickColor,
          borderColor:'rgba(0,0,0,0.08)', borderWidth:1, padding:12, cornerRadius:12,
          callbacks:{ label: c => ' ৳' + c.raw.toLocaleString() }
        }
      },
      scales:{
        x:{ grid:{display:false}, ticks:{font:{size:11,family:'DM Sans'},color:tickColor}, border:{display:false} },
        y:{ grid:{color:gridColor,drawBorder:false}, ticks:{font:{size:11,family:'DM Sans'},color:tickColor}, border:{display:false} }
      }
    }
  });
}
function loadChart(months, btn) {
  document.querySelectorAll('.period-btn').forEach(b => {
    b.classList.remove('active');
  });
  btn.classList.add('active');
  fetch(`/dashboard/chart-data?months=${months}`)
    .then(r => r.json()).then(d => buildChart(d.labels, d.data)).catch(()=>{});
}

/* ── Expense Donut ── */
function buildDonut() {
  const ctx2 = document.getElementById('expenseDonut');
  if (!ctx2) return;
  const isDark = document.documentElement.classList.contains('dark');
  const cats = @json($expenseSummary ?? [['name'=>'No Data','amount'=>1,'color'=>'#EDE9E0']]);
  new Chart(ctx2, {
    type:'doughnut',
    data:{
      labels: cats.map(c => c.name),
      datasets:[{
        data: cats.map(c => c.amount || 1),
        backgroundColor: cats.map(c => c.color),
        borderWidth: 0, hoverOffset:8, borderRadius:4,
      }]
    },
    options:{
      responsive:true, maintainAspectRatio:false, cutout:'76%',
      plugins:{
        legend:{ display:false },
        tooltip:{ callbacks:{ label: c => ' ৳' + c.raw.toLocaleString() } }
      }
    }
  });
}

/* ── Count-up animation (dark-mode safe) ── */
function runCountUp() {
  document.querySelectorAll('[data-countup]').forEach(el => {
    const target = parseFloat(el.dataset.target) || 0;
    const duration = 1200;
    const start = performance.now();
    function tick(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const ease = 1 - Math.pow(1 - progress, 3);
      const val = Math.round(ease * target);
      el.textContent = val.toLocaleString();
      if (progress < 1) requestAnimationFrame(tick);
      else el.textContent = target.toLocaleString();
    }
    requestAnimationFrame(tick);
  });
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', () => {
  const labels = @json($chartLabels ?? []);
  const data   = @json($chartData   ?? []);
  if (labels.length) buildChart(labels, data);
  buildDonut();
  setTimeout(runCountUp, 300);
});

/* Re-init charts if theme toggles */
document.addEventListener('themeChanged', () => {
  const labels = @json($chartLabels ?? []);
  const data   = @json($chartData   ?? []);
  if (labels.length) buildChart(labels, data);
});
</script>
@endpush