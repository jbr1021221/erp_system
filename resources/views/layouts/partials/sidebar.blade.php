<aside
  class="fixed top-0 left-0 bottom-0 z-50 flex flex-col w-[260px] transition-transform duration-300"
  style="background:var(--bg-sidebar); overflow:hidden;"
  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>

  {{-- Decorative glows --}}
  <div style="position:absolute;top:-60px;right:-60px;width:180px;height:180px;background:radial-gradient(circle,rgba(212,80,30,0.25) 0%,transparent 70%);pointer-events:none;"></div>
  <div style="position:absolute;bottom:-60px;left:-40px;width:160px;height:160px;background:radial-gradient(circle,rgba(26,82,118,0.2) 0%,transparent 70%);pointer-events:none;"></div>

  {{-- Logo --}}
  <div class="flex items-center gap-3 px-5 pt-6 pb-5">
    <div style="width:40px;height:40px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:18px;color:white;">E</span>
    </div>
    <div>
      <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:white;letter-spacing:-0.3px;">EduERP</div>
      <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.3);margin-top:-1px;">Institution Suite</div>
    </div>
  </div>

  {{-- Nav --}}
  <nav class="flex-1 px-3 overflow-y-auto" style="padding-bottom:12px;">

    {{-- Overview --}}
    <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.22);padding:16px 10px 6px;">Overview</div>
    <x-nav-item route="dashboard" icon="grid" label="Dashboard" />

    {{-- Academics --}}
    <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.22);padding:16px 10px 6px;">Academics</div>
    <x-nav-item route="students.index" icon="users" label="Students" />
    <x-nav-item route="teachers.index" icon="user" label="Teachers" />
    <x-nav-item route="classes.index" icon="book" label="Classes" />

    {{-- Finance --}}
    <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.22);padding:16px 10px 6px;">Finance</div>
    <x-nav-item route="payments.index" icon="dollar" label="Income / Fees" :badge="$pendingFeesCount ?? null" />
    <x-nav-item route="fee-structures.index" icon="list" label="Fee Structure" />
    <x-nav-item route="expenses.index" icon="receipt" label="Expenses" />

    {{-- System --}}
    <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.22);padding:16px 10px 6px;">System</div>
    <x-nav-item route="users.index" icon="person" label="Users" />
    <x-nav-item route="roles.index" icon="shield" label="Roles & Perms" />
    <x-nav-item route="settings.index" icon="cog" label="Settings" />
  </nav>

  {{-- User footer --}}
  <div class="px-3 pb-5 pt-3" style="border-top:1px solid rgba(255,255,255,0.07);">
    <div class="flex items-center gap-3 p-3 rounded-xl cursor-pointer" style="background:rgba(255,255,255,0.05);">
      @php
        $name = auth()->user()->name ?? 'Admin';
        $initials = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ', $name), 0, 2)));
      @endphp
      <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--accent-gold));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:white;flex-shrink:0;">
        {{ $initials }}
      </div>
      <div class="flex-1 min-w-0">
        <div style="font-size:13px;font-weight:500;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $name }}</div>
        <div style="font-size:10px;color:rgba(255,255,255,0.35);">{{ auth()->user()->role ?? 'Super Admin' }}</div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" title="Logout" style="color:rgba(255,255,255,0.3);transition:color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        </button>
      </form>
    </div>
  </div>
</aside>
