@extends('layouts.app')
@section('page-title', 'Reports')
@section('breadcrumb', 'System · Reports')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">Reports</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">Generate and export detailed reports for your institution</p>
  </div>
  <div style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:12px;color:var(--text-muted);">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    {{ now()->format('D, M j Y') }}
  </div>
</div>

{{-- Report Categories --}}
@php
$reportGroups = [
  [
    'title'   => 'Academic Reports',
    'icon'    => 'M12 14l9-5-9-5-9 5 9 5z',
    'color'   => '#6366f1',
    'reports' => [
      ['name'=>'Student Report',    'desc'=>'All enrolled students with class, section & status', 'route'=>'reports.students', 'icon'=>'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8z'],
    ],
  ],
  [
    'title'   => 'Finance Reports',
    'icon'    => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'color'   => '#10b981',
    'reports' => [
      ['name'=>'Payments Report',   'desc'=>'All payment records with filters by date & student', 'route'=>'reports.payments',  'icon'=>'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
      ['name'=>'Expenses Report',   'desc'=>'Expenses categorised by type and date range',        'route'=>'reports.expenses',  'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
      ['name'=>'Financial Summary', 'desc'=>'Income vs expenses overview with monthly breakdown',  'route'=>'reports.financial', 'icon'=>'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
      ['name'=>'Fee Report',        'desc'=>'Fee structures, assignments & outstanding balances',  'route'=>'reports.fees',      'icon'=>'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
    ],
  ],
];
@endphp

<div class="space-y-6">
@foreach($reportGroups as $group)
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">

  {{-- Group header --}}
  <div style="padding:16px 22px;border-bottom:1px solid var(--border-color);background:var(--bg-surface-2);display:flex;align-items:center;gap:12px;">
    <div style="width:36px;height:36px;border-radius:10px;background:{{ $group['color'] }}18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="17" height="17" fill="none" stroke="{{ $group['color'] }}" stroke-width="1.8" viewBox="0 0 24 24">
        @foreach(explode(' M', ' '.$group['icon']) as $i => $part)
          @if($i > 0)<path d="M{{ $part }}"/>@endif
        @endforeach
      </svg>
    </div>
    <div>
      <h3 style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text-primary);margin:0;">{{ $group['title'] }}</h3>
    </div>
  </div>

  {{-- Report cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-0">
    @foreach($group['reports'] as $i => $report)
    @php
      $routeExists = \Illuminate\Support\Facades\Route::has($report['route']);
    @endphp
    <div style="padding:20px 22px;{{ $i % 2 == 0 || $i % 4 == 0 ? '' : '' }}border-right:1px solid var(--border-color);border-bottom:1px solid var(--border-color);">
      <div style="display:flex;align-items:flex-start;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;background:{{ $group['color'] }}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
          <svg width="16" height="16" fill="none" stroke="{{ $group['color'] }}" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="{{ $report['icon'] }}"/>
          </svg>
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:3px;font-family:'Syne',sans-serif;">{{ $report['name'] }}</div>
          <div style="font-size:12px;color:var(--text-muted);line-height:1.5;margin-bottom:12px;">{{ $report['desc'] }}</div>
          @if($routeExists)
            <a href="{{ route($report['route']) }}"
               style="display:inline-flex;align-items:center;gap:5px;padding:6px 13px;background:{{ $group['color'] }}18;border:1px solid {{ $group['color'] }}30;border-radius:8px;font-size:12px;font-weight:600;color:{{ $group['color'] }};text-decoration:none;transition:all 0.2s;"
               onmouseover="this.style.background='{{ $group['color'] }}28'" onmouseout="this.style.background='{{ $group['color'] }}18'">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
              View Report
            </a>
          @else
            <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 13px;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:8px;font-size:12px;font-weight:600;color:var(--text-muted);">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Coming Soon
            </span>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>

</div>
@endforeach
</div>

{{-- Quick export section --}}
<div style="margin-top:24px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;padding:22px 24px;">
  <h3 style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text-primary);margin:0 0 4px;">Quick Export</h3>
  <p style="font-size:13px;color:var(--text-muted);margin:0 0 18px;">Export common datasets directly without filters</p>
  <div class="flex flex-wrap gap-3">
    @foreach([
      ['Students (CSV)',         'reports.students',  '#6366f1', 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
      ['Payments (CSV)',         'reports.payments',  '#10b981', 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
      ['Expenses (CSV)',         'reports.expenses',  '#f59e0b', 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
      ['Financial Summary (CSV)','reports.financial', '#f43f5e', 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
      ['Fee Report (CSV)',       'reports.fees',      '#8b5cf6', 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
    ] as [$label, $route, $color, $icon])
    @php $exists = \Illuminate\Support\Facades\Route::has($route); @endphp
    @if($exists)
      <a href="{{ route($route) }}"
         style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:{{ $color }}15;border:1px solid {{ $color }}30;border-radius:10px;font-size:13px;font-weight:600;color:{{ $color }};text-decoration:none;transition:all 0.2s;"
         onmouseover="this.style.background='{{ $color }}25'" onmouseout="this.style.background='{{ $color }}15'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
        {{ $label }}
      </a>
    @else
      <span style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:10px;font-size:13px;font-weight:600;color:var(--text-muted);opacity:0.6;cursor:not-allowed;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
        {{ $label }}
      </span>
    @endif
    @endforeach
  </div>
</div>

@endsection