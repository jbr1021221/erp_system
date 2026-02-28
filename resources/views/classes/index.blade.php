@extends('layouts.app')
@section('page-title', 'Classes')
@section('breadcrumb', 'Academics · Classes & Sections')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Academic Classes</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Manage classes, sections, and assigned teachers</p>
  </div>
  <a href="{{ route('classes.create') }}"
     style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:var(--accent);border-radius:10px;font-size:13px;font-weight:600;color:white;text-decoration:none;transition:all 0.2s;"
     onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Class
  </a>
</div>

{{-- Stats Strip --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 animate-in delay-1">
  @php
    $stats = [
      ['label' => 'Total Classes', 'value' => count($classes ?? []), 'color' => 'var(--accent-info)'],
      ['label' => 'Total Students', 'value' => $totalStudents ?? 0, 'color' => 'var(--accent)'],
      ['label' => 'Total Sections', 'value' => $totalSections ?? 0, 'color' => 'var(--accent-green)'],
      ['label' => 'Avg per Class', 'value' => ($avgStudents ?? 0), 'color' => 'var(--accent-gold)'],
    ];
  @endphp
  @foreach($stats as $s)
  <div class="card p-4 flex items-center justify-between" style="border-left:3px solid {{ $s['color'] }};">
    <span style="font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ $s['label'] }}</span>
    <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:20px;color:var(--text-primary);">{{ $s['value'] }}</span>
  </div>
  @endforeach
</div>

{{-- Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 animate-in delay-2">
  @forelse($classes ?? [] as $i => $class)
  <div class="card p-6 relative overflow-hidden group">
    {{-- Number Badge --}}
    <div style="position:absolute;top:0;left:0;width:40px;height:40px;background:var(--bg-surface-2);border-radius:0 0 16px 0;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--text-secondary);border-right:1px solid var(--border-color);border-bottom:1px solid var(--border-color);">
      {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
    </div>

    <div class="pl-8 flex justify-between items-start mb-5">
      <h3 style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:var(--text-primary);">{{ $class->name }}</h3>
      <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
        <a href="{{ route('classes.edit', $class) }}" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);border:1px solid var(--border-color);border-radius:6px;transition:all 0.2s;" onmouseover="this.style.color='var(--accent-gold)';this.style.background='rgba(201,168,76,0.1)'" onmouseout="this.style.color='var(--text-muted)';this.style.background='transparent'"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
      </div>
    </div>

    <div class="flex flex-col gap-3">
      <div class="flex items-center justify-between p-3 rounded-xl" style="background:var(--bg-surface-2);">
        <div class="flex items-center gap-2">
          <svg width="16" height="16" fill="none" stroke="var(--accent-info)" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
          <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Students</span>
        </div>
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">{{ $class->students_count ?? 0 }}</div>
      </div>
      <div class="flex items-center justify-between p-3 rounded-xl" style="background:var(--bg-surface-2);">
        <div class="flex items-center gap-2">
          <svg width="16" height="16" fill="none" stroke="var(--accent-green)" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
          <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Sections</span>
        </div>
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">{{ $class->sections_count ?? 1 }}</div>
      </div>
    </div>

    <div class="mt-4 pt-4 flex flex-col gap-3" style="border-top:1px solid var(--border-color);">
      <div>
        <div style="font-size:11px;color:var(--text-muted);font-weight:500;margin-bottom:2px;">Class Teacher</div>
        <div style="font-size:13px;font-weight:600;color:var(--text-primary);">{{ $class->teacher->name ?? 'Not Assigned' }}</div>
      </div>
      <a href="{{ route('students.index', ['class_id' => $class->id]) }}"
         style="display:block;width:100%;height:38px;line-height:36px;text-align:center;border:1px solid var(--border-color);border-radius:10px;font-size:13px;font-weight:600;color:var(--text-secondary);text-decoration:none;transition:all 0.2s;"
         onmouseover="this.style.background='var(--bg-surface-2)';this.style.color='var(--text-primary)'" onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
        View Students →
      </a>
    </div>
  </div>
  @empty
  <div class="col-span-full py-20 text-center">
    <div style="color:var(--text-muted);margin-bottom:12px;"><svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" style="margin:0 auto;"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg></div>
    <div style="font-weight:600;font-size:16px;color:var(--text-primary);">No classes found</div>
    <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">Create classes and sections to organize your students.</div>
    <a href="{{ route('classes.create') }}" style="display:inline-block;margin-top:16px;padding:9px 20px;background:var(--accent);color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">Add First Class</a>
  </div>
  @endforelse
</div>

@endsection
