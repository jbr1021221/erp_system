@extends('layouts.app')
@section('page-title', 'Student Report')
@section('breadcrumb', 'Reports · Students')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">Student Report</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">All enrolled students with class and status</p>
  </div>
  <div class="flex gap-2">
    <a href="{{ route('reports.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 14px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='var(--bg-surface)'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg> Back
    </a>
    <button onclick="window.print()" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);cursor:pointer;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='var(--bg-surface)'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg> Print
    </button>
  </div>
</div>

{{-- Filters --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;padding:16px;margin-bottom:16px;">
  <form method="GET" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[160px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Class</label>
      <select name="class_id" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Classes</option>
        @foreach($classes as $class)
          <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="min-w-[140px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Status</label>
      <select name="status" style="width:100%;height:40px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Status</option>
        <option value="active"      {{ request('status')=='active'      ? 'selected':'' }}>Active</option>
        <option value="inactive"    {{ request('status')=='inactive'    ? 'selected':'' }}>Inactive</option>
        <option value="transferred" {{ request('status')=='transferred' ? 'selected':'' }}>Transferred</option>
        <option value="graduated"   {{ request('status')=='graduated'   ? 'selected':'' }}>Graduated</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" style="height:40px;padding:0 18px;background:linear-gradient(135deg,#6366f1,#818cf8);color:white;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">Filter</button>
      <a href="{{ route('reports.students') }}" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;">Clear</a>
    </div>
  </form>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
  @foreach([['Total',$total,'#6366f1'],['Active',$active,'#4ade80'],['Inactive',$inactive,'#f87171'],['Other',$other,'#fb923c']] as [$label,$count,$color])
  <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:10px;">
    <div style="width:8px;height:36px;border-radius:4px;background:{{ $color }};flex-shrink:0;"></div>
    <div>
      <div style="font-size:22px;font-weight:800;font-family:'Syne',sans-serif;color:{{ $color }};">{{ $count }}</div>
      <div style="font-size:11px;color:var(--text-muted);">{{ $label }}</div>
    </div>
  </div>
  @endforeach
</div>

{{-- Table --}}
<div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
  <div class="overflow-x-auto">
    <table class="w-full" style="border-collapse:collapse;">
      <thead>
        <tr style="background:var(--bg-surface-2);border-bottom:1px solid var(--border-color);">
          @foreach(['#','Student','ID','Class','Guardian','Phone','Enrolled','Status'] as $h)
          <th style="padding:12px 16px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--text-muted);font-weight:600;text-align:left;">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($students as $i => $student)
        @php
          $name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->name ?? 'Unknown');
          $statusColors = ['active'=>'#4ade80','inactive'=>'#f87171','transferred'=>'#94a3b8','graduated'=>'#fbbf24'];
          $sc = $statusColors[$student->status ?? 'active'] ?? '#94a3b8';
        @endphp
        <tr style="border-bottom:1px solid var(--border-color);transition:background 0.15s;" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='transparent'">
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ $i + 1 }}</td>
          <td style="padding:12px 16px;font-size:13px;font-weight:600;color:var(--text-primary);">{{ $name }}</td>
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);font-family:monospace;">{{ $student->student_id ?? '—' }}</td>
          <td style="padding:12px 16px;font-size:13px;color:var(--text-secondary);">{{ optional($student->class)->name ?? '—' }}</td>
          <td style="padding:12px 16px;font-size:13px;color:var(--text-secondary);">{{ $student->guardian_name ?? '—' }}</td>
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ $student->guardian_phone ?? '—' }}</td>
          <td style="padding:12px 16px;font-size:12px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($student->enrollment_date ?? $student->created_at)->format('M j, Y') }}</td>
          <td style="padding:12px 16px;">
            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 9px;background:{{ $sc }}18;border:1px solid {{ $sc }}35;border-radius:6px;font-size:11px;font-weight:600;color:{{ $sc }};">
              <span style="width:5px;height:5px;border-radius:50%;background:{{ $sc }};"></span>
              {{ ucfirst($student->status ?? 'active') }}
            </span>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="padding:50px;text-align:center;color:var(--text-muted);font-size:13px;">No students found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px 16px;border-top:1px solid var(--border-color);">
    <span style="font-size:12px;color:var(--text-muted);">{{ $total }} student(s) found</span>
  </div>
</div>

@endsection