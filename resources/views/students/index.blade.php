@extends('layouts.app')
@section('page-title', 'Students')
@section('breadcrumb', 'Academics · All Students')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">All Students</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">
      Manage enrolled students ·
      <span style="color:#4ade80;font-weight:600;">
        {{ $students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->total() : count($students ?? []) }} total
      </span>
    </p>
  </div>
  <div class="flex gap-2">
    <a href="javascript:void(0)"
       style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;font-weight:500;color:var(--text-secondary);text-decoration:none;transition:all 0.2s;backdrop-filter:blur(8px);"
       onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
      Export
    </a>
    <a href="{{ route('students.create') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:linear-gradient(135deg,#D4501E,#e8622d);border-radius:10px;font-size:13px;font-weight:600;color:white;text-decoration:none;transition:all 0.2s;box-shadow:0 4px 15px rgba(212,80,30,0.3);"
       onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(212,80,30,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(212,80,30,0.3)'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Student
    </a>
  </div>
</div>

{{-- Stats Strip --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
  @php
    $totalStudents = $students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->total() : count($students ?? []);
    $activeCount   = ($students ?? collect())->where('status','active')->count();
    $inactiveCount = ($students ?? collect())->where('status','inactive')->count();
    $otherCount    = $totalStudents - $activeCount - $inactiveCount;
  @endphp
  @foreach([
    ['Total',    $totalStudents, '#818cf8', 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2 M9 7a4 4 0 100 8 4 4 0 000-8z M23 21v-2a4 4 0 00-3-3.87 M16 3.13a4 4 0 010 7.75'],
    ['Active',   $activeCount,   '#4ade80', 'M22 11.08V12a10 10 0 11-5.93-9.14 M22 4L12 14.01l-3-3'],
    ['Inactive', $inactiveCount, '#f87171', 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ['Other',    $otherCount,    '#fb923c', 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
  ] as [$label, $count, $color, $icon])
  <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;">
    <div style="width:36px;height:36px;border-radius:9px;background:{{ $color }}18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="16" height="16" fill="none" stroke="{{ $color }}" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
    </div>
    <div>
      <div style="font-size:20px;font-weight:700;font-family:'Syne',sans-serif;color:{{ $color }};line-height:1;">{{ $count }}</div>
      <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $label }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Filters --}}
<div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:16px;margin-bottom:16px;">
  <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[200px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Search</label>
      <div class="relative">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, ID, phone..."
          style="width:100%;height:40px;padding:0 12px 0 36px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;background:rgba(255,255,255,0.05);color:var(--text-primary);outline:none;transition:border-color 0.2s;"
          onfocus="this.style.borderColor='#D4501E'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
      </div>
    </div>
    <div class="min-w-[140px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Class</label>
      <select name="class_id" style="width:100%;height:40px;padding:0 12px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;background:rgba(255,255,255,0.05);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Classes</option>
        @foreach($classes ?? [] as $class)
          <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="min-w-[130px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Status</label>
      <select name="status" style="width:100%;height:40px;padding:0 12px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;background:rgba(255,255,255,0.05);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Status</option>
        <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
        <option value="transferred" {{ request('status')=='transferred' ? 'selected' : '' }}>Transferred</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" style="height:40px;padding:0 18px;background:linear-gradient(135deg,#D4501E,#e8622d);color:white;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Filter</button>
      <a href="{{ route('students.index') }}" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">Clear</a>
    </div>
  </form>
</div>

{{-- Table --}}
<div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;">
  <div class="overflow-x-auto">
    <table class="w-full" style="border-collapse:collapse;">
      <thead>
        <tr style="background:rgba(255,255,255,0.04);border-bottom:1px solid rgba(255,255,255,0.08);">
          <th style="width:44px;padding:13px 16px;">
            <input type="checkbox" id="selectAll" style="accent-color:#D4501E;width:15px;height:15px;cursor:pointer;">
          </th>
          <th class="text-left" style="padding:13px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;">Student</th>
          <th class="text-left" style="padding:13px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;">Class</th>
          <th class="text-left" style="padding:13px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;">Guardian</th>
          <th class="text-left" style="padding:13px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;">Admitted</th>
          <th class="text-left" style="padding:13px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;">Status</th>
          <th class="text-right" style="padding:13px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students ?? [] as $student)
        @php
          // Use full_name accessor (first_name + last_name)
          $displayName = $student->full_name ?? trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->name ?? 'Unknown');
          $initials = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ', $displayName), 0, 2)));
          $gi = (ord($displayName[0] ?? 'A') - 65) % 8;
          $grads = [
            ['#6366f1','#818cf8'],['#059669','#34d399'],['#0284c7','#38bdf8'],
            ['#b45309','#fbbf24'],['#be123c','#fb7185'],['#7c3aed','#a78bfa'],
            ['#0e7490','#22d3ee'],['#c2410c','#fb923c'],
          ];
          $g = $grads[$gi];
          // Use 'class' relationship (not 'schoolClass')
          $className = optional($student->class)->name ?? optional($student->schoolClass)->name ?? '—';
          $sectionName = optional($student->section)->name ?? ($student->section_name ?? '');
        @endphp
        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);transition:background 0.15s;"
            onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
          <td style="padding:14px 16px;">
            <input type="checkbox" class="row-check" style="accent-color:#D4501E;width:15px;height:15px;cursor:pointer;">
          </td>
          <td style="padding:14px 16px;">
            <div class="flex items-center gap-3">
              <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,{{ $g[0] }},{{ $g[1] }});display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:white;flex-shrink:0;box-shadow:0 2px 8px {{ $g[0] }}40;">
                {{ $initials }}
              </div>
              <div>
                <a href="{{ route('students.show', $student) }}"
                   style="font-size:14px;font-weight:600;color:var(--text-primary);text-decoration:none;transition:color 0.2s;"
                   onmouseover="this.style.color='#e8622d'" onmouseout="this.style.color='var(--text-primary)'">
                  {{ $displayName }}
                </a>
                <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">{{ $student->student_id ?? 'N/A' }}</div>
              </div>
            </div>
          </td>
          <td style="padding:14px 16px;">
            @if($className !== '—')
              <span style="display:inline-block;padding:3px 10px;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.25);border-radius:6px;font-size:12px;font-weight:600;color:#818cf8;">
                {{ $className }}
              </span>
              @if($sectionName)
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">Section {{ $sectionName }}</div>
              @endif
            @else
              <span style="color:var(--text-muted);font-size:13px;">—</span>
            @endif
          </td>
          <td style="padding:14px 16px;">
            <div style="font-size:13px;font-weight:500;color:var(--text-secondary);">{{ $student->guardian_name ?? '—' }}</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">{{ $student->guardian_phone ?? '' }}</div>
          </td>
          <td style="padding:14px 16px;font-size:12px;color:var(--text-muted);">
            {{ \Carbon\Carbon::parse($student->enrollment_date ?? $student->admission_date ?? $student->created_at)->format('M j, Y') }}
          </td>
          <td style="padding:14px 16px;">
            @php
              $statusColors = [
                'active'      => ['bg'=>'rgba(74,222,128,0.12)','border'=>'rgba(74,222,128,0.3)','text'=>'#4ade80'],
                'inactive'    => ['bg'=>'rgba(248,113,113,0.12)','border'=>'rgba(248,113,113,0.3)','text'=>'#f87171'],
                'transferred' => ['bg'=>'rgba(148,163,184,0.12)','border'=>'rgba(148,163,184,0.3)','text'=>'#94a3b8'],
                'graduated'   => ['bg'=>'rgba(251,191,36,0.12)','border'=>'rgba(251,191,36,0.3)','text'=>'#fbbf24'],
                'suspended'   => ['bg'=>'rgba(251,146,60,0.12)','border'=>'rgba(251,146,60,0.3)','text'=>'#fb923c'],
              ];
              $s = $statusColors[$student->status ?? 'active'] ?? $statusColors['active'];
            @endphp
            <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:{{ $s['bg'] }};border:1px solid {{ $s['border'] }};border-radius:6px;font-size:11px;font-weight:600;color:{{ $s['text'] }};text-transform:uppercase;letter-spacing:0.5px;">
              <span style="width:5px;height:5px;border-radius:50%;background:{{ $s['text'] }};"></span>
              {{ ucfirst($student->status ?? 'active') }}
            </span>
          </td>
          <td style="padding:14px 16px;">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('students.show', $student) }}" title="View"
                 style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;color:var(--text-muted);transition:all 0.2s;"
                 onmouseover="this.style.background='rgba(56,189,248,0.1)';this.style.color='#38bdf8'" onmouseout="this.style.background='transparent';this.style.color='var(--text-muted)'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('students.edit', $student) }}" title="Edit"
                 style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;color:var(--text-muted);transition:all 0.2s;"
                 onmouseover="this.style.background='rgba(251,191,36,0.1)';this.style.color='#fbbf24'" onmouseout="this.style.background='transparent';this.style.color='var(--text-muted)'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete {{ $displayName }}?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" title="Delete"
                  style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;border:none;background:transparent;color:var(--text-muted);cursor:pointer;transition:all 0.2s;"
                  onmouseover="this.style.background='rgba(248,113,113,0.1)';this.style.color='#f87171'" onmouseout="this.style.background='transparent';this.style.color='var(--text-muted)'">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="padding:70px 20px;text-align:center;">
            <div style="color:var(--text-muted);display:flex;flex-direction:column;align-items:center;gap:10px;">
              <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" style="opacity:0.4;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              </div>
              <div style="font-weight:600;font-size:15px;color:var(--text-secondary);">No students found</div>
              <div style="font-size:13px;">Try adjusting your filters or add a new student</div>
              <a href="{{ route('students.create') }}" style="margin-top:8px;padding:9px 20px;background:linear-gradient(135deg,#D4501E,#e8622d);color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">Add First Student</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if(method_exists($students ?? [], 'links') && $students->hasPages())
  <div class="flex items-center justify-between px-5 py-4" style="border-top:1px solid rgba(255,255,255,0.07);">
    <span style="font-size:12px;color:var(--text-muted);">
      Showing {{ $students->firstItem() }}–{{ $students->lastItem() }} of {{ $students->total() }} students
    </span>
    <div>{{ $students->links() }}</div>
  </div>
  @endif
</div>

<script>
  // Select all checkbox
  document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
  });
</script>

@endsection