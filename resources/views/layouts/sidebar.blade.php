{{--
  layouts/sidebar.blade.php
  EduERP Sidebar — Emerald & Teal Theme
  Fonts: Outfit (brand) + Plus Jakarta Sans (nav labels)
--}}

<aside
  id="sidebar"
  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
  class="fixed top-0 left-0 z-50 h-screen w-[260px] flex flex-col transition-transform duration-300 ease-in-out"
  style="background: var(--bg-sidebar); border-right: 1px solid var(--border-color);"
>

  {{-- ── Logo ── --}}
  <div class="flex items-center gap-3 px-6 py-5"
       style="border-bottom: 1px solid var(--border-color);">
    <div class="w-9 h-9 rounded-md flex items-center justify-center flex-shrink-0"
         style="background: var(--accent);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
           stroke="white" stroke-width="2.5"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
      </svg>
    </div>
    <div>
      <div style="font-family:'Outfit',sans-serif; font-weight:700; font-size:16px;
                  color:var(--text-primary); letter-spacing:-0.3px;">EduERP</div>
      <div style="font-size:10px; color:var(--text-muted); letter-spacing:1.5px;
                  text-transform:uppercase; font-weight:600;
                  font-family:'Plus Jakarta Sans',sans-serif;">Institution Suite</div>
    </div>
  </div>

  {{-- ── Nav ── --}}
  <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5"
       style="scrollbar-width:thin; scrollbar-color: #059669 transparent;">

    {{-- Overview --}}
    <div class="px-3 mb-2 pt-2"
         style="font-size:10px; font-weight:700; letter-spacing:1.5px;
                text-transform:uppercase; color:var(--text-muted);
                font-family:'Plus Jakarta Sans',sans-serif;">Overview</div>

    <x-sidebar-link route="dashboard" label="Dashboard">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
      </svg>
    </x-sidebar-link>

    {{-- Academics --}}
    <div class="px-3 pt-6 pb-2"
         style="font-size:10px; font-weight:700; letter-spacing:1.5px;
                text-transform:uppercase; color:var(--text-muted);
                font-family:'Plus Jakarta Sans',sans-serif;">Academics</div>

    @can('student-list')
    <x-sidebar-link route="students.index" routePattern="students.*" label="Students">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('teacher-list')
    <x-sidebar-link route="teachers.index" routePattern="teachers.*" label="Teachers">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="8" r="4"/>
        <path d="M20 21a8 8 0 1 0-16 0"/>
        <line x1="12" y1="12" x2="12" y2="16"/>
        <line x1="10" y1="15" x2="14" y2="15"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('class-list')
    <x-sidebar-link route="classes.index" routePattern="classes.*" label="Classes">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
      </svg>
    </x-sidebar-link>
    @endcan

    {{-- Finance --}}
    <div class="px-3 pt-6 pb-2"
         style="font-size:10px; font-weight:700; letter-spacing:1.5px;
                text-transform:uppercase; color:var(--text-muted);
                font-family:'Plus Jakarta Sans',sans-serif;">Finance</div>

    @can('payment-list')
    <x-sidebar-link route="payments.index" routePattern="payments.*" label="Income / Fees">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="1" x2="12" y2="23"/>
        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('fee-structure-list')
    <x-sidebar-link route="fee-structures.index" routePattern="fee-structures.*" label="Fee Structure">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/>
        <line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('expense-list')
    <x-sidebar-link route="expenses.index" routePattern="expenses.*" label="Expenses">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="5" width="20" height="14" rx="2"/>
        <line x1="2" y1="10" x2="22" y2="10"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('account-list')
    <x-sidebar-link route="accounts.index" routePattern="accounts.*" label="Accounts">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
      </svg>
    </x-sidebar-link>
    @endcan

    {{-- System --}}
    <div class="px-3 pt-6 pb-2"
         style="font-size:10px; font-weight:700; letter-spacing:1.5px;
                text-transform:uppercase; color:var(--text-muted);
                font-family:'Plus Jakarta Sans',sans-serif;">System</div>

    @can('user-list')
    <x-sidebar-link route="users.index" routePattern="users.*" label="Users">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('role-list')
    <x-sidebar-link route="roles.index" routePattern="roles.*" label="Roles & Perms">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
      </svg>
    </x-sidebar-link>
    @endcan

    @can('report-view')
    <x-sidebar-link route="reports.index" routePattern="reports.*" label="Reports">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="20" x2="18" y2="10"/>
        <line x1="12" y1="20" x2="12" y2="4"/>
        <line x1="6" y1="20" x2="6" y2="14"/>
        <line x1="2" y1="20" x2="22" y2="20"/>
      </svg>
    </x-sidebar-link>
    @endcan

    <x-sidebar-link route="settings.index" routePattern="settings*" label="Settings">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
      </svg>
    </x-sidebar-link>

  </nav>

  {{-- ── User profile footer ── --}}
  <div class="px-4 py-4" style="border-top: 1px solid var(--border-color);">
    <div class="flex items-center gap-3">
      {{-- Avatar --}}
      <div class="w-9 h-9 rounded-md flex items-center justify-center flex-shrink-0
                  text-white text-sm font-bold"
           style="background: var(--accent);
                  font-family:'Outfit',sans-serif; font-weight:700;">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <div style="font-size:13px; font-weight:600; color:var(--text-primary);
                    font-family:'Plus Jakarta Sans',sans-serif;" class="truncate">
          {{ Auth::user()->name }}
        </div>
        <div style="font-size:11px; color:var(--text-muted);
                    font-family:'Plus Jakarta Sans',sans-serif;" class="truncate">
          {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
        </div>
      </div>
      {{-- Logout --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" title="Sign out"
                class="w-8 h-8 rounded-md flex items-center justify-center transition-all duration-200"
                style="color:var(--text-muted);"
                onmouseover="this.style.background='var(--bg-surface-2)';this.style.color='var(--text-primary)'"
                onmouseout="this.style.background='transparent';this.style.color='var(--text-muted)'">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </button>
      </form>
    </div>
  </div>

</aside>