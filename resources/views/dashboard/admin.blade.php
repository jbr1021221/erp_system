@extends('layouts.app')
@section('title', 'Admin Dashboard')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ══════════════════════════════════════════════
   EMERALD & TEAL THEME
   Primary:   #059669 (emerald-600)
   Secondary: #0d9488 (teal-600)
   Accent hi: #34d399 (emerald-400)
   Hero:      clean light card, border + subtle gradient
   Fonts:     Outfit (display) + Plus Jakarta Sans (ui)
══════════════════════════════════════════════ */
:root {
  --em:       #059669;
  --em-dark:  #047857;
  --em-light: #d1fae5;
  --tl:       #0d9488;
  --tl-dark:  #0f766e;
  --tl-light: #ccfbf1;
  --hi:       #34d399;

  --font-display: 'Outfit', sans-serif;
  --font-ui:      'Plus Jakarta Sans', sans-serif;
}

.dash-root, .dash-root * { font-family: var(--font-ui) !important; }

/* ── HERO — clean light card ── */
.hero {
  position: relative; overflow: hidden;
  border-radius: 20px; padding: 28px 32px;
  background: var(--bg-surface);
  border: 1px solid var(--border-color);
  box-shadow: var(--shadow-sm);
  margin-bottom: 22px;
}
/* Subtle teal-to-transparent wash on the right */
.hero::before {
  content: '';
  position: absolute; inset: 0; pointer-events: none; border-radius: 20px;
  background: linear-gradient(120deg,
    transparent 40%,
    rgba(13,148,136,0.05) 70%,
    rgba(5,150,105,0.08) 100%);
}
/* Decorative corner arc */
.hero-arc {
  position: absolute; right: -60px; top: -60px;
  width: 280px; height: 280px; border-radius: 50%; pointer-events: none;
  border: 40px solid rgba(5,150,105,0.06);
}
.hero-arc-2 {
  position: absolute; right: 30px; bottom: -80px;
  width: 160px; height: 160px; border-radius: 50%; pointer-events: none;
  border: 28px solid rgba(13,148,136,0.05);
}
/* Tiny dot grid */
.hero-dots {
  position: absolute; inset: 0; pointer-events: none; border-radius: 20px;
  background-image: radial-gradient(rgba(5,150,105,0.12) 1px, transparent 1px);
  background-size: 24px 24px;
  -webkit-mask-image: linear-gradient(to right, transparent 0%, black 30%, black 70%, transparent 100%);
  mask-image: linear-gradient(to right, transparent 0%, black 30%, black 70%, transparent 100%);
  opacity: 0.4;
}
.hero-eyebrow {
  display: inline-flex; align-items: center; gap: 6px;
  font-family: var(--font-ui) !important;
  font-size: 10px; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--em); margin-bottom: 9px;
}
.hero-eyebrow span {
  display: inline-block; width: 18px; height: 2px;
  background: linear-gradient(90deg, var(--em), var(--tl)); border-radius: 2px;
}
.hero-greeting {
  font-family: var(--font-display) !important;
  font-weight: 700; font-size: 24px;
  color: var(--text-primary); letter-spacing: -0.04em; line-height: 1.2;
}
.dark .hero-greeting { color: #EDE7DE !important; }
.hero-sub {
  font-family: var(--font-ui) !important;
  font-size: 12.5px; font-weight: 500;
  color: var(--text-muted); margin-top: 6px;
}
/* Stat pills inside hero */
.hero-pill {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 16px; border-radius: 12px;
  background: var(--bg-surface-2);
  border: 1px solid var(--border-color);
  transition: all 0.18s;
}
.hero-pill:hover { border-color: rgba(5,150,105,0.3); box-shadow: 0 2px 12px rgba(5,150,105,0.1); }
.hero-pill-val {
  font-family: var(--font-display) !important;
  font-weight: 800; font-size: 20px; letter-spacing: -0.04em;
  color: var(--text-primary); line-height: 1;
}
.dark .hero-pill-val { color: #EDE7DE !important; }
.hero-pill-label {
  font-family: var(--font-ui) !important;
  font-size: 10px; font-weight: 600; color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.8px;
}
.hero-pill-icon {
  width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
}
.hero-btn-primary {
  padding: 9px 20px; border-radius: 11px;
  background: linear-gradient(135deg, var(--em), var(--tl));
  color: #fff; font-size: 12.5px; font-weight: 700;
  text-decoration: none; white-space: nowrap;
  box-shadow: 0 3px 16px rgba(5,150,105,0.3); transition: all 0.18s;
  font-family: var(--font-ui) !important;
}
.hero-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 22px rgba(5,150,105,0.38); }
.hero-btn-ghost {
  padding: 9px 20px; border-radius: 11px;
  background: transparent; border: 1px solid var(--border-color);
  color: var(--text-secondary); font-size: 12.5px; font-weight: 600;
  text-decoration: none; white-space: nowrap; transition: all 0.18s;
  font-family: var(--font-ui) !important;
}
.hero-btn-ghost:hover { border-color: rgba(5,150,105,0.4); color: var(--em); background: rgba(5,150,105,0.04); }

/* ── STAT CARDS ── */
.stat-card {
  position: relative; overflow: hidden;
  background: var(--bg-surface); border: 1px solid var(--border-color);
  border-radius: 16px; padding: 20px 20px 18px;
  transition: all 0.22s; box-shadow: var(--shadow-sm);
}
.stat-card::after {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  border-radius: 16px 16px 0 0;
}
.stat-card.c-em::after   { background: linear-gradient(90deg, var(--em), var(--hi)); }
.stat-card.c-tl::after   { background: linear-gradient(90deg, var(--tl), #22d3ee); }
.stat-card.c-blue::after { background: linear-gradient(90deg, #2563eb, #60a5fa); }
.stat-card.c-rose::after { background: linear-gradient(90deg, #e11d48, #fb7185); }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 34px rgba(0,0,0,0.1); }

.s-label {
  font-family: var(--font-ui) !important;
  font-size: 10px; font-weight: 700; letter-spacing: 0.9px;
  text-transform: uppercase; color: var(--text-muted);
}
.s-value {
  font-family: var(--font-display) !important;
  font-weight: 800; line-height: 1; margin-top: 9px;
  color: var(--text-primary); letter-spacing: -0.04em;
}
.s-value.xl { font-size: 38px; }
.s-value.lg { font-size: 30px; }

/* Dark mode — explicit on every text element */
.dark .s-value       { color: #EDE7DE !important; }
.dark .s-label       { color: #6A6158 !important; }
.dark .panel-title   { color: #EDE7DE !important; }
.dark .panel-sub     { color: #6A6158 !important; }
.dark .td-primary    { color: #EDE7DE !important; }
.dark .td-secondary  { color: #9A9088 !important; }
.dark .td-muted      { color: #6A6158 !important; }
.dark .td-amount     { color: #EDE7DE !important; }
.dark .th-label      { color: #6A6158 !important; }
.dark .class-name    { color: #EDE7DE !important; }
.dark .class-count   { color: #6A6158 !important; }
.dark .class-rank    { background: #EDE7DE !important; color: #1A1614 !important; }
.dark .hero-pill-val { color: #EDE7DE !important; }
.dark .hero-greeting { color: #EDE7DE !important; }

.s-icon {
  width: 42px; height: 42px; border-radius: 11px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.s-icon.em   { background: rgba(5,150,105,0.1); }
.s-icon.tl   { background: rgba(13,148,136,0.1); }
.s-icon.blue { background: rgba(37,99,235,0.1); }
.s-icon.rose { background: rgba(225,29,72,0.1); }

/* ── PANELS ── */
.panel {
  background: var(--bg-surface); border: 1px solid var(--border-color);
  border-radius: 18px; overflow: hidden;
  box-shadow: var(--shadow-sm); transition: box-shadow 0.22s;
}
.panel:hover { box-shadow: var(--shadow-md); }
.panel-head {
  padding: 17px 22px; border-bottom: 1px solid var(--border-color);
  display: flex; align-items: center; justify-content: space-between;
}
.panel-title {
  font-family: var(--font-display) !important;
  font-weight: 700; font-size: 14px;
  color: var(--text-primary); letter-spacing: -0.02em;
}
.panel-sub {
  font-family: var(--font-ui) !important;
  font-size: 11px; color: var(--text-muted); margin-top: 2px;
}

.period-wrap {
  display: flex; gap: 2px; background: var(--bg-surface-2); border-radius: 10px; padding: 3px;
}
.period-btn {
  padding: 4px 12px; border-radius: 7px; border: none; cursor: pointer;
  font-family: var(--font-ui) !important; font-size: 10.5px; font-weight: 700;
  transition: all 0.18s; color: var(--text-muted); background: transparent;
}
.period-btn.active {
  background: linear-gradient(135deg, var(--em), var(--tl));
  color: #fff; box-shadow: 0 2px 9px rgba(5,150,105,0.3);
}

/* ── CLASS ROWS ── */
.class-row {
  display: flex; align-items: center; gap: 11px; padding: 8px 11px;
  border-radius: 11px; background: var(--bg-surface-2); transition: all 0.14s;
}
.class-row:hover { background: var(--bg-base); transform: translateX(3px); }
.class-rank {
  width: 24px; height: 24px; border-radius: 6px; background: var(--text-primary);
  color: var(--bg-base); display: flex; align-items: center; justify-content: center;
  font-family: var(--font-display) !important; font-weight: 800; font-size: 9px; flex-shrink: 0;
}
.class-name {
  font-family: var(--font-ui) !important; font-size: 12.5px; font-weight: 600;
  color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.class-count {
  font-family: var(--font-display) !important; font-weight: 700; font-size: 13px;
  color: var(--text-muted); flex-shrink: 0;
}
.class-bar-track {
  flex: 1; height: 3px; border-radius: 99px;
  background: rgba(0,0,0,0.07); overflow: hidden;
}
.dark .class-bar-track { background: rgba(255,255,255,0.07); }

/* ── TABLES ── */
.data-table { width: 100%; border-collapse: collapse; }
.data-table thead tr { background: var(--bg-surface-2); }
.th-label {
  text-align: left; padding: 9px 18px;
  font-family: var(--font-ui) !important; font-size: 9.5px;
  text-transform: uppercase; letter-spacing: 1.3px;
  color: var(--text-muted); font-weight: 700;
}
.data-table tbody tr { border-bottom: 1px solid var(--border-color); transition: background 0.14s; }
.data-table tbody tr:hover { background: var(--bg-surface-2); }
.data-table td { padding: 12px 18px; }
.td-primary   { font-family: var(--font-ui) !important; font-size: 13px; font-weight: 600; color: var(--text-primary); }
.td-secondary { font-family: var(--font-ui) !important; font-size: 11px; font-weight: 500; color: var(--text-secondary); }
.td-muted     { font-family: var(--font-ui) !important; font-size: 11.5px; font-weight: 500; color: var(--text-muted); }
.td-amount    { font-family: var(--font-display) !important; font-weight: 700; font-size: 14.5px; letter-spacing: -0.03em; color: var(--text-primary); }

.avatar {
  width: 34px; height: 34px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-display) !important; font-weight: 800; font-size: 11px;
  color: white; flex-shrink: 0;
}
.badge { font-family: var(--font-ui) !important; font-size: 10.5px !important; font-weight: 700 !important; }
/* Override badge colours to teal theme */
.badge-green { background: rgba(5,150,105,0.1) !important; color: var(--em) !important; }

.view-link {
  font-family: var(--font-ui) !important; font-size: 11px; font-weight: 700;
  text-decoration: none; padding: 4px 12px; border-radius: 7px; transition: all 0.16s;
}

/* Animations */
@keyframes fadeUp { from{opacity:0;transform:translateY(13px)} to{opacity:1;transform:translateY(0)} }
.au{animation:fadeUp 0.36s ease both}
.d1{animation-delay:.04s}.d2{animation-delay:.08s}.d3{animation-delay:.12s}
.d4{animation-delay:.16s}.d5{animation-delay:.20s}.d6{animation-delay:.24s}
.d7{animation-delay:.28s}.d8{animation-delay:.32s}
@keyframes growW{from{width:0}}
.pbar{border-radius:99px;animation:growW .85s ease both;animation-delay:.45s}
.slim-scroll::-webkit-scrollbar{width:3px}
.slim-scroll::-webkit-scrollbar-track{background:transparent}
.slim-scroll::-webkit-scrollbar-thumb{background:var(--em);border-radius:99px}
</style>
@endpush

@section('content')
<div class="dash-root">

{{-- ══════════════════════════════════════
     HERO — clean light card
══════════════════════════════════════ --}}
<div class="hero au d1">
  <div class="hero-dots"></div>
  <div class="hero-arc"></div>
  <div class="hero-arc-2"></div>

  <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6" style="position:relative;z-index:1;">

    {{-- Left: greeting --}}
    <div class="flex-1">
      <div class="hero-eyebrow">
        <span></span>
        {{ now()->format('l, F j · Y') }}
      </div>
      <div class="hero-greeting">
        {{ now()->hour < 12 ? '☀️' : (now()->hour < 17 ? '🌤' : '🌙') }}
        Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
        {{ auth()->user()->name }}
      </div>
      <div class="hero-sub">Academic Year {{ date('Y') }}–{{ date('Y')+1 }} &nbsp;·&nbsp; EduERP Admin Suite</div>

      <div class="flex gap-2.5 mt-5">
        <a href="{{ route('students.create') }}" class="hero-btn-ghost">+ Add Student</a>
        <a href="{{ route('payments.create') }}" class="hero-btn-primary">Collect Fee</a>
      </div>
    </div>

    {{-- Right: 3 mini stat pills --}}
    <div class="flex flex-wrap gap-3 lg:flex-nowrap">
      <div class="hero-pill">
        <div class="hero-pill-icon" style="background:rgba(5,150,105,0.1);">
          <svg width="16" height="16" fill="none" stroke="var(--em)" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div>
          <div class="hero-pill-label">Students</div>
          <div class="hero-pill-val">{{ $stats['total_students'] }}</div>
        </div>
      </div>
      <div class="hero-pill">
        <div class="hero-pill-icon" style="background:rgba(13,148,136,0.1);">
          <svg width="16" height="16" fill="none" stroke="var(--tl)" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div>
          <div class="hero-pill-label">Revenue</div>
          <div class="hero-pill-val" style="font-size:17px;">৳{{ number_format($stats['total_revenue'] / 1000, 0) }}k</div>
        </div>
      </div>
      <div class="hero-pill">
        <div class="hero-pill-icon" style="background:rgba(37,99,235,0.08);">
          <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8L2 7h20z"/></svg>
        </div>
        <div>
          <div class="hero-pill-label">Today</div>
          <div class="hero-pill-val" style="font-size:17px;">৳{{ number_format($stats['today_revenue']) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     STAT CARDS
══════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">

  <div class="stat-card c-em au d1">
    <div class="flex items-start justify-between mb-3">
      <div>
        <div class="s-label">Total Students</div>
        <div class="s-value xl">{{ $stats['total_students'] }}</div>
      </div>
      <div class="s-icon em">
        <svg width="20" height="20" fill="none" stroke="var(--em)" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
      </div>
    </div>
    <div class="flex items-center gap-2 mt-1">
      <span class="badge badge-green">{{ $stats['active_students'] }} Active</span>
      <span style="font-size:11px;color:var(--text-muted);">{{ $stats['inactive_students'] }} Inactive</span>
    </div>
  </div>

  <div class="stat-card c-tl au d2">
    <div class="flex items-start justify-between mb-3">
      <div>
        <div class="s-label">Total Revenue</div>
        <div class="s-value lg">৳{{ number_format($stats['total_revenue']) }}</div>
      </div>
      <div class="s-icon tl">
        <svg width="20" height="20" fill="none" stroke="var(--tl)" stroke-width="1.8" viewBox="0 0 24 24">
          <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
        </svg>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--em);font-weight:700;">
      <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
      +৳{{ number_format($stats['this_month_revenue']) }} this month
    </div>
  </div>

  <div class="stat-card c-blue au d3">
    <div class="flex items-start justify-between mb-3">
      <div>
        <div class="s-label">Today's Collection</div>
        <div class="s-value xl">৳{{ number_format($stats['today_revenue']) }}</div>
      </div>
      <div class="s-icon blue">
        <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="1.8" viewBox="0 0 24 24">
          <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 3H8L2 7h20z"/>
        </svg>
      </div>
    </div>
    <div style="font-size:11px;color:var(--text-muted);">Collected today from payments</div>
  </div>

  <div class="stat-card c-rose au d4">
    <div class="flex items-start justify-between mb-3">
      <div>
        <div class="s-label">Outstanding Fees</div>
        <div class="s-value lg">৳{{ number_format($stats['total_outstanding']) }}</div>
      </div>
      <div class="s-icon rose">
        <svg width="20" height="20" fill="none" stroke="#e11d48" stroke-width="1.8" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
      </div>
    </div>
    @if($stats['pending_expenses'] > 0)
      <a href="{{ route('payments.index', ['status' => 'pending']) }}" style="font-size:11px;font-weight:700;color:#e11d48;text-decoration:none;">
        {{ $stats['pending_expenses'] }} pending expenses →
      </a>
    @else
      <span style="font-size:11px;color:var(--em);font-weight:700;">✓ No pending expenses</span>
    @endif
  </div>
</div>

{{-- ══════════════════════════════════════
     REVENUE CHART + BY CLASS
══════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-5">

  <div class="panel lg:col-span-3 au d5">
    <div class="panel-head">
      <div>
        <div class="panel-title">Revenue Trend</div>
        <div class="panel-sub">Monthly income overview</div>
      </div>
      <div class="period-wrap">
        <button class="period-btn" onclick="loadChart(3,this)">3M</button>
        <button class="period-btn active" onclick="loadChart(6,this)">6M</button>
        <button class="period-btn" onclick="loadChart(12,this)">1Y</button>
      </div>
    </div>
    <div class="p-5">
      <div style="position:relative;height:210px;">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
  </div>

  <div class="panel lg:col-span-2 au d5">
    <div class="panel-head">
      <div class="panel-title">Students by Class</div>
      <a href="{{ route('classes.index') }}" class="view-link"
         style="color:var(--em);background:rgba(5,150,105,0.08);"
         onmouseover="this.style.background='rgba(5,150,105,0.15)'"
         onmouseout="this.style.background='rgba(5,150,105,0.08)'">View All →</a>
    </div>
    <div class="p-4">
      <div class="flex flex-col gap-2 slim-scroll" style="max-height:256px;overflow-y:auto;">
        @php
          $palette = ['#059669','#0d9488','#2563eb','#7c3aed','#db2777','#d97706','#16a34a','#0284c7'];
          $maxSt   = collect($classWiseStudents)->max('students_count') ?: 1;
        @endphp
        @forelse($classWiseStudents as $i => $class)
        <div class="class-row">
          <div class="class-rank">{{ $i + 1 }}</div>
          <div style="flex:1;min-width:0;">
            <div class="class-name">{{ $class->name }}</div>
            <div class="class-bar-track" style="margin-top:4px;">
              <div class="pbar" style="height:3px;width:{{ ($class->students_count / $maxSt) * 100 }}%;background:{{ $palette[$i % count($palette)] }};"></div>
            </div>
          </div>
          <div class="class-count">{{ $class->students_count }}</div>
        </div>
        @empty
        <p style="text-align:center;padding:20px 0;font-size:12px;color:var(--text-muted);">No classes found</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════
     RECENT TABLES
══════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 au d7">

  {{-- Recent Admissions --}}
  <div class="panel overflow-hidden">
    <div class="panel-head">
      <div class="panel-title">Recent Admissions</div>
      @can('student-list')
      <a href="{{ route('students.index') }}" class="view-link"
         style="color:var(--em);background:rgba(5,150,105,0.08);"
         onmouseover="this.style.background='rgba(5,150,105,0.15)'"
         onmouseout="this.style.background='rgba(5,150,105,0.08)'">View All →</a>
      @endcan
    </div>
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead><tr>
          <th class="th-label">Student</th>
          <th class="th-label">Class</th>
          <th class="th-label">Joined</th>
          <th class="th-label">Status</th>
        </tr></thead>
        <tbody>
          @forelse($recentAdmissions as $student)
          @php
            $sname    = $student->full_name ?? $student->name ?? '?';
            $initials = collect(explode(' ', $sname))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
            $gi       = (ord($sname[0] ?? 'A') - 65) % 8;
            $grads    = [['#059669','#34d399'],['#0d9488','#2dd4bf'],['#2563eb','#60a5fa'],['#7c3aed','#a78bfa'],['#db2777','#f472b6'],['#d97706','#fbbf24'],['#059669','#0d9488'],['#0284c7','#38bdf8']];
            $g        = $grads[$gi];
            $st       = $student->status ?? 'active';
          @endphp
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                @if(isset($student->photo) && $student->photo)
                  <img src="{{ Storage::url($student->photo) }}" alt="{{ $sname }}"
                       style="width:34px;height:34px;border-radius:9px;object-fit:cover;flex-shrink:0;">
                @else
                  <div class="avatar" style="background:linear-gradient(135deg,{{ $g[0] }},{{ $g[1] }});">{{ $initials }}</div>
                @endif
                <div>
                  <div class="td-primary">{{ $sname }}</div>
                  <div class="td-secondary">{{ $student->student_id ?? $student->roll_number ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td class="td-muted">{{ $student->class->name ?? '—' }}</td>
            <td class="td-muted">{{ \Carbon\Carbon::parse($student->created_at)->format('M j, Y') }}</td>
            <td>
              <span class="badge {{ $st === 'active' ? 'badge-green' : ($st === 'transferred' ? 'badge-muted' : 'badge-gold') }}">
                {{ ucfirst($st) }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="padding:36px;text-align:center;" class="td-muted">No recent admissions</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Recent Payments --}}
  <div class="panel overflow-hidden">
    <div class="panel-head">
      <div class="panel-title">Recent Payments</div>
      @can('payment-list')
      <a href="{{ route('payments.index') }}" class="view-link"
         style="color:var(--tl);background:rgba(13,148,136,0.08);"
         onmouseover="this.style.background='rgba(13,148,136,0.15)'"
         onmouseout="this.style.background='rgba(13,148,136,0.08)'">View All →</a>
      @endcan
    </div>
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead><tr>
          <th class="th-label">Student</th>
          <th class="th-label">Amount</th>
          <th class="th-label">Receipt</th>
          <th class="th-label">Date</th>
        </tr></thead>
        <tbody>
          @forelse($recentPayments as $payment)
          @php
            $pname  = $payment->student->full_name ?? $payment->student->name ?? 'Unknown';
            $pi     = collect(explode(' ', $pname))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
            $pgi    = (ord($pname[0] ?? 'A') - 65) % 8;
            $pg     = $grads[$pgi] ?? ['#059669','#34d399'];
            $status = $payment->status ?? 'paid';
            $sColor = match(true) {
              in_array($status, ['completed','paid']) => '#059669',
              $status === 'partial'                   => '#d97706',
              default                                 => '#e11d48',
            };
          @endphp
          <tr style="border-left:2.5px solid {{ $sColor }};">
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div class="avatar" style="background:linear-gradient(135deg,{{ $pg[0] }},{{ $pg[1] }});">{{ $pi }}</div>
                <div>
                  <div class="td-primary">{{ $pname }}</div>
                  <div class="td-secondary">{{ ucfirst($payment->payment_method ?? '—') }}</div>
                </div>
              </div>
            </td>
            <td><span class="td-amount">৳{{ number_format($payment->amount ?? 0) }}</span></td>
            <td class="td-muted">{{ $payment->receipt_number ?? '—' }}</td>
            <td class="td-muted">{{ isset($payment->payment_date) ? \Carbon\Carbon::parse($payment->payment_date)->format('M j') : '—' }}</td>
          </tr>
          @empty
          <tr><td colspan="4" style="padding:36px;text-align:center;" class="td-muted">No recent payments</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>{{-- /dash-root --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let revenueChart;
const isDark = () =>
  document.documentElement.classList.contains('dark') ||
  document.documentElement.getAttribute('data-theme') === 'dark';

function buildChart(labels, data) {
  const ctx = document.getElementById('revenueChart')?.getContext('2d');
  if (!ctx) return;
  const dark = isDark();
  if (revenueChart) revenueChart.destroy();

  // Emerald-to-teal gradient fill
  const grad = ctx.createLinearGradient(0, 0, 0, 210);
  grad.addColorStop(0, 'rgba(5,150,105,0.18)');
  grad.addColorStop(0.6, 'rgba(13,148,136,0.08)');
  grad.addColorStop(1, 'rgba(13,148,136,0.00)');

  const tick = dark ? '#6A6158' : '#9A9088';
  const grid = dark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.04)';

  revenueChart = new Chart(ctx, {
    type: 'line',
    data: { labels, datasets: [{
      data,
      borderColor: '#059669',
      borderWidth: 2.5,
      backgroundColor: grad,
      fill: true, tension: 0.42,
      pointBackgroundColor: '#059669',
      pointBorderColor: dark ? '#1E1812' : '#fff',
      pointBorderWidth: 3, pointRadius: 5, pointHoverRadius: 7,
    }]},
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: dark ? '#1E1812' : '#fff',
          titleColor: dark ? '#EDE7DE' : '#1A1614',
          bodyColor: tick,
          borderColor: dark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.07)',
          borderWidth: 1, padding: 12, cornerRadius: 10, displayColors: false,
          titleFont: { family: "'Plus Jakarta Sans',sans-serif", size: 11, weight: '700' },
          bodyFont:  { family: "'Plus Jakarta Sans',sans-serif", size: 12 },
          callbacks: { label: c => ' ৳' + c.raw.toLocaleString() }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { size: 10.5, family: "'Plus Jakarta Sans',sans-serif", weight: '600' }, color: tick },
          border: { display: false }
        },
        y: {
          beginAtZero: true,
          grid: { color: grid, drawBorder: false },
          ticks: {
            font: { size: 10.5, family: "'Plus Jakarta Sans',sans-serif", weight: '600' }, color: tick,
            callback: v => '৳' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
          },
          border: { display: false }
        }
      },
      interaction: { intersect: false, mode: 'index' }
    }
  });
}

function loadChart(months, btn) {
  document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  fetch(`/dashboard/chart-data?months=${months}`)
    .then(r => r.json()).then(d => buildChart(d.labels, d.data)).catch(() => {});
}

document.addEventListener('DOMContentLoaded', () => {
  const raw = @json($monthlyRevenue);
  if (raw?.length) buildChart(raw.map(r => r.month), raw.map(r => r.total));
});
document.addEventListener('themeChanged', () => {
  const raw = @json($monthlyRevenue);
  if (raw?.length) buildChart(raw.map(r => r.month), raw.map(r => r.total));
});
</script>
@endpush