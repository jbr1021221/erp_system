@extends('layouts.app')
@section('page-title', 'Fee Structure')
@section('breadcrumb', 'Finance · Fees Framework')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">Fee Structure</a>
  {{-- <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">Expenses</a> --}}
@endsection


@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Fee Structure</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Pre-defined fees and tuition plans</p>
  </div>
  <a href="{{ route('fee-structures.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors flex items-center gap-1.5 shadow-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Fee Type
  </a>
</div>

{{-- Fee Grid --}}
@php
  $accents = ['var(--accent)','var(--accent-green)','var(--accent-gold)','var(--accent-info)'];
@endphp

@if(isset($feeStructures) && $feeStructures->count())
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 animate-in delay-1">
  @foreach($feeStructures as $i => $fee)
  @php $ac = $accents[$i % count($accents)]; @endphp
  <div class="card p-6" style="border-top:4px solid {{ $ac }};display:flex;flex-direction:column;">
    <div class="flex items-start justify-between mb-2">
      <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text-primary);">{{ $fee->fee_type }}</h3>
      <div class="flex gap-1 border border-[var(--border-color)] rounded-lg p-1">
        <a href="{{ route('fee-structures.edit', $fee) }}" title="Edit"
           style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);border-radius:6px;transition:all 0.2s;"
           onmouseover="this.style.color='var(--accent-gold)';this.style.background='rgba(201,168,76,0.1)'"
           onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </a>
        <form action="{{ route('fee-structures.destroy', $fee) }}" method="POST" onsubmit="return confirm('Delete this fee?')" style="display:inline;">
          @csrf @method('DELETE')
          <button type="submit"
            style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);border-radius:6px;background:none;border:none;cursor:pointer;transition:all 0.2s;"
            onmouseover="this.style.color='var(--accent)';this.style.background='rgba(212,80,30,0.1)'"
            onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
          </button>
        </form>
      </div>
    </div>

    <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:28px;color:var(--text-primary);letter-spacing:-1px;margin-bottom:4px;">
      ৳{{ number_format($fee->amount ?? 0) }}
    </div>

    <div style="margin-bottom:12px;">
      <span style="display:inline-block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;padding:2px 8px;border-radius:99px;background:var(--bg-surface-2);color:var(--text-muted);border:1px solid var(--border-color);">
        {{ ucfirst(str_replace('_', ' ', $fee->frequency)) }}
      </span>
    </div>

    <div style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin-bottom:20px;flex-grow:1;">
      {{ Str::limit($fee->description ?? 'No description provided for this fee structure.', 100) }}
    </div>

    <div style="border-top:1px solid var(--border-color);padding-top:16px;">
      <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Applicable Class</div>
      <div class="flex flex-wrap gap-2">
        <span class="badge" style="background:var(--bg-surface-2);color:var(--text-secondary);border:1px solid var(--border-color);">
          {{ $fee->class->name ?? 'All Classes' }}
        </span>
      </div>
    </div>
  </div>
  @endforeach
</div>

@else

{{-- Empty State --}}
<div class="flex items-center justify-center" style="min-height: calc(100vh - 220px);">
  <div class="text-center max-w-sm mx-auto px-4">

    {{-- Icon --}}
    <div style="display:inline-flex;align-items:center;justify-content:center;width:72px;height:72px;border-radius:24px;background:var(--bg-surface-2);color:var(--text-muted);margin-bottom:20px;">
      <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
      </svg>
    </div>

    {{-- Title --}}
    <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text-primary);margin-bottom:8px;">
      No fee structures found
    </h3>

    {{-- Subtitle --}}
    <p style="font-size:13px;color:var(--text-muted);line-height:1.6;margin-bottom:24px;">
      Define standard fees to assign them to students during admission.
    </p>

    {{-- CTA --}}
    <a href="{{ route('fee-structures.create') }}"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-5 py-2.5 text-sm font-medium transition-colors shadow-sm">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Add First Fee Type
    </a>

  </div>
</div>

@endif

@endsection