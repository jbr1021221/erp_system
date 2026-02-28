@extends('layouts.app')
@section('page-title', 'Teachers')
@section('breadcrumb', 'Academics · All Teachers')

@section('subnav')
  <a href="{{ route('students.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('students.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Students</a>
  <a href="{{ route('teachers.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('teachers.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Teachers</a>
  <a href="{{ route('classes.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('classes.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800'}}">Classes</a>
@endsection


@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:24px;color:var(--text-primary);letter-spacing:-0.5px;">All Teachers</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:3px;">
      Manage teaching staff ·
      <span style="color:#4ade80;font-weight:600;">
        {{ $teachers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $teachers->total() : count($teachers ?? []) }} total
      </span>
    </p>
  </div>
  <a href="{{ route('teachers.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors flex items-center gap-1.5 shadow-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Teacher
  </a>
</div>

{{-- Filters --}}
<div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:16px;margin-bottom:16px;">
  <form method="GET" action="{{ route('teachers.index') }}" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[200px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Search</label>
      <div class="relative">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, phone, subject..."
          style="width:100%;height:40px;padding:0 12px 0 36px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;background:rgba(255,255,255,0.05);color:var(--text-primary);outline:none;transition:border-color 0.2s;"
          onfocus="this.style.borderColor='#D4501E'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
      </div>
    </div>
    <div class="min-w-[140px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Department</label>
      <select name="department" style="width:100%;height:40px;padding:0 12px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;background:rgba(255,255,255,0.05);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Departments</option>
        @foreach($departments ?? [] as $dept)
          <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
        @endforeach
      </select>
    </div>
    <div class="min-w-[130px]">
      <label style="font-size:10px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px;text-transform:uppercase;letter-spacing:1px;">Status</label>
      <select name="status" style="width:100%;height:40px;padding:0 12px;border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;background:rgba(255,255,255,0.05);color:var(--text-primary);outline:none;cursor:pointer;">
        <option value="">All Status</option>
        <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors">Filter</button>
      <a href="{{ route('teachers.index') }}" style="height:40px;padding:0 14px;display:inline-flex;align-items:center;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">Clear</a>
    </div>
  </form>
</div>

{{-- Table --}}
<div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:14px;overflow:hidden;">
  <div class="overflow-x-auto">
    <table class="w-full" style="border-collapse:collapse;">
      <thead>
        <tr class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 font-medium border-b border-slate-200">
          <th style="width:44px;" class="px-4 py-3">
            <input type="checkbox" id="selectAll" style="accent-color:#D4501E;width:15px;height:15px;cursor:pointer;">
          </th>
          <th class="text-left px-4 py-3">Teacher</th>
          <th class="text-left px-4 py-3">Designation</th>
          <th class="text-left px-4 py-3">Subject & Dept</th>
          <th class="text-left px-4 py-3">Qualification</th>
          <th class="text-left px-4 py-3">Classes</th>
          <th class="text-left px-4 py-3">Status</th>
          <th class="text-right px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($teachers ?? [] as $teacher)
        @php
          $displayName = $teacher->name ?? trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Unknown';
          $initials = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ', $displayName), 0, 2)));
          $gi = (ord($displayName[0] ?? 'A') - 65) % 8;
          $grads = [
            ['#6366f1','#818cf8'],['#059669','#34d399'],['#0284c7','#38bdf8'],
            ['#b45309','#fbbf24'],['#be123c','#fb7185'],['#7c3aed','#a78bfa'],
            ['#0e7490','#22d3ee'],['#c2410c','#fb923c'],
          ];
          $g = $grads[$gi];

          // Teacher model uses 'designation'; subject/department/qualification are optional columns
          $designation   = $teacher->designation ?? '—';
          $subject       = $teacher->subject       ?? null;
          $department    = $teacher->department    ?? null;
          $qualification = $teacher->qualification ?? null;

          // Status: model uses 'is_active' boolean, but may also have 'status' string
          $isActive = isset($teacher->status)
              ? $teacher->status === 'active'
              : (bool)($teacher->is_active ?? true);

          $classCount = count($teacher->classes ?? []);
        @endphp
        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3.5">
            <input type="checkbox" class="row-check" style="accent-color:#D4501E;width:15px;height:15px;cursor:pointer;">
          </td>

          {{-- Teacher name + phone --}}
          <td class="px-4 py-3.5">
            <div class="flex items-center gap-3">
              <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,{{ $g[0] }},{{ $g[1] }});display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:white;flex-shrink:0;box-shadow:0 2px 8px {{ $g[0] }}40;">
                {{ $initials }}
              </div>
              <div>
                <a href="{{ route('teachers.show', $teacher) }}"
                   style="font-size:14px;font-weight:600;color:var(--text-primary);text-decoration:none;transition:color 0.2s;"
                   onmouseover="this.style.color='#e8622d'" onmouseout="this.style.color='var(--text-primary)'">
                  {{ $displayName }}
                </a>
                <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">{{ $teacher->phone ?? 'N/A' }}</div>
              </div>
            </div>
          </td>

          {{-- Designation (what the DB actually has) --}}
          <td class="px-4 py-3.5">
            @if($designation !== '—')
              <span style="display:inline-block;padding:3px 10px;background:rgba(212,80,30,0.12);border:1px solid rgba(212,80,30,0.25);border-radius:6px;font-size:12px;font-weight:600;color:#e8622d;">
                {{ $designation }}
              </span>
            @else
              <span style="color:var(--text-muted);font-size:13px;">—</span>
            @endif
          </td>

          {{-- Subject & Dept (optional columns) --}}
          <td class="px-4 py-3.5">
            @if($subject || $department)
              <div style="font-size:13px;font-weight:500;color:var(--text-secondary);">{{ $subject ?? '—' }}</div>
              @if($department)
                <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">{{ $department }}</div>
              @endif
            @else
              {{-- Graceful: show designation here instead so column isn't blank --}}
              <span style="font-size:12px;color:var(--text-muted);font-style:italic;">Not set</span>
            @endif
          </td>

          {{-- Qualification (optional column) --}}
          <td class="px-4 py-3.5">
            @if($qualification)
              <div style="font-size:13px;color:var(--text-secondary);">{{ $qualification }}</div>
            @else
              <span style="font-size:12px;color:var(--text-muted);font-style:italic;">Not set</span>
            @endif
          </td>

          {{-- Assigned classes --}}
          <td class="px-4 py-3.5">
            <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:rgba(148,163,184,0.1);border:1px solid rgba(148,163,184,0.2);border-radius:6px;font-size:12px;font-weight:600;color:#94a3b8;">
              <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              {{ $classCount }} {{ Str::plural('class', $classCount) }}
            </span>
          </td>

          {{-- Status --}}
          <td class="px-4 py-3.5">
            @if($isActive)
              <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.3);border-radius:6px;font-size:11px;font-weight:600;color:#4ade80;text-transform:uppercase;letter-spacing:0.5px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#4ade80;"></span> Active
              </span>
            @else
              <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.3);border-radius:6px;font-size:11px;font-weight:600;color:#f87171;text-transform:uppercase;letter-spacing:0.5px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#f87171;"></span> Inactive
              </span>
            @endif
          </td>

          {{-- Actions --}}
          <td class="px-4 py-3.5">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('teachers.show', $teacher) }}" title="View"
                 class="text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-md p-1.5 transition">
                <svg width="15" height="15" fill="none" class="stroke-current" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="{{ route('teachers.edit', $teacher) }}" title="Edit"
                 class="text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md p-1.5 transition">
                <svg width="15" height="15" fill="none" class="stroke-current" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Delete {{ $displayName }}?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" title="Delete"
                  class="text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md p-1.5 transition">
                  <svg width="15" height="15" fill="none" class="stroke-current" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="padding:70px 20px;text-align:center;">
            <div style="color:var(--text-muted);display:flex;flex-direction:column;align-items:center;gap:10px;">
              <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" style="opacity:0.4;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
              <div style="font-weight:600;font-size:15px;color:var(--text-secondary);">No teachers found</div>
              <div style="font-size:13px;">Try adjusting your filters or add a new teacher</div>
              <a href="{{ route('teachers.create') }}" class="mt-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-[background-color]">Add First Teacher</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if(method_exists($teachers ?? [], 'links') && $teachers->hasPages())
  <div class="flex items-center justify-between px-5 py-4" style="border-top:1px solid rgba(255,255,255,0.07);">
    <span style="font-size:12px;color:var(--text-muted);">
      Showing {{ $teachers->firstItem() }}–{{ $teachers->lastItem() }} of {{ $teachers->total() }} teachers
    </span>
    <div>{{ $teachers->links() }}</div>
  </div>
  @endif
</div>

<script>
  document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
  });
</script>

@endsection