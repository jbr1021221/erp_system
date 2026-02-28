@extends('layouts.app')
@section('page-title', 'Fee Structure')
@section('breadcrumb', 'Finance · Fees Framework')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Fee Structure</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Pre-defined fees and tuition plans</p>
  </div>
  <a href="{{ route('fee-structures.create') }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--accent);border-radius:10px;font-size:13px;font-weight:600;color:white;text-decoration:none;transition:all 0.2s;"
     onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Fee Type
  </a>
</div>

{{-- Fee Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 animate-in delay-1">
  @php
    $accents = ['var(--accent)','var(--accent-green)','var(--accent-gold)','var(--accent-info)'];
  @endphp
  @forelse($feeStructures ?? [] as $i => $fee)
  @php $ac = $accents[$i % count($accents)]; @endphp
  <div class="card p-6" style="border-top:4px solid {{ $ac }};display:flex;flex-direction:column;">
    <div class="flex items-start justify-between mb-2">
      <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text-primary);">{{ $fee->name }}</h3>
      <div class="flex gap-1 border border-[var(--border-color)] rounded-lg p-1">
        <a href="{{ route('fee-structures.edit', $fee) }}" title="Edit" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);border-radius:6px;transition:all 0.2s;" onmouseover="this.style.color='var(--accent-gold)';this.style.background='rgba(201,168,76,0.1)'" onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent'"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
        <form action="{{ route('fee-structures.destroy', $fee) }}" method="POST" onsubmit="return confirm('Delete this fee?')" style="display:inline;">@csrf @method('DELETE')<button type="submit" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);border-radius:6px;background:none;border:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.color='var(--accent)';this.style.background='rgba(212,80,30,0.1)'" onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent'"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg></button></form>
      </div>
    </div>
    <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:28px;color:var(--text-primary);letter-spacing:-1px;margin-bottom:12px;">
      ৳{{ number_format($fee->amount ?? 0) }}
    </div>
    <div style="font-size:13px;color:var(--text-secondary);line-height:1.5;margin-bottom:20px;flex-grow:1;">
      {{ Str::limit($fee->description ?? 'No description provided for this fee structure.', 100) }}
    </div>

    <div style="border-top:1px solid var(--border-color);padding-top:16px;">
      <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Applicable Classes</div>
      <div class="flex flex-wrap gap-2">
        @forelse($fee->classes ?? [1,2,3] as $c)
          <span class="badge" style="background:var(--bg-surface-2);color:var(--text-secondary);border:1px solid var(--border-color);">{{ is_numeric($c) ? 'Class '.$c : ($c->name ?? 'Class') }}</span>
        @empty
          <span class="badge" style="background:var(--bg-surface-2);color:var(--text-secondary);border:1px solid var(--border-color);">All Classes</span>
        @endforelse
      </div>
    </div>
  </div>
  @empty
  <div class="col-span-full py-20 text-center">
    <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:20px;background:var(--bg-surface-2);color:var(--text-muted);margin-bottom:16px;">
      <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
    </div>
    <h3 style="font-weight:600;font-size:16px;color:var(--text-primary);">No fee structures found</h3>
    <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Define standard fees to assign them to students during admission.</p>
    <a href="{{ route('fee-structures.create') }}" style="display:inline-block;margin-top:16px;padding:9px 20px;background:var(--accent);color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">Add First Fee Type</a>
  </div>
  @endforelse
</div>

@endsection
