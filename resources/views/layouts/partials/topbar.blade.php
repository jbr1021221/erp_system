<header class="sticky top-0 z-30 flex items-center gap-4 px-6 lg:px-8 h-16"
  style="background:var(--bg-base);border-bottom:1px solid var(--border-color);">

  {{-- Mobile hamburger --}}
  <button class="lg:hidden p-2 rounded-xl" style="background:var(--bg-surface);border:1px solid var(--border-color);"
    @click="sidebarOpen = !sidebarOpen">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>

  {{-- Page title --}}
  <div class="flex-1">
    <h1 style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:var(--text-primary);letter-spacing:-0.3px;line-height:1.2;">
      @yield('page-title', 'Dashboard')
    </h1>
    @hasSection('breadcrumb')
      <div style="font-size:11px;color:var(--text-muted);margin-top:1px;">@yield('breadcrumb')</div>
    @endif
  </div>

  {{-- Right actions --}}
  <div class="flex items-center gap-2">

    {{-- Date chip --}}
    <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
      style="background:var(--bg-surface);border:1px solid var(--border-color);color:var(--text-secondary);">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      {{ now()->format('D, M j, Y') }}
    </div>

    {{-- Theme toggle --}}
    <button onclick="toggleTheme()"
      class="flex items-center justify-center w-9 h-9 rounded-xl transition-all"
      style="background:var(--bg-surface);border:1px solid var(--border-color);color:var(--text-secondary);"
      title="Toggle theme">
      <svg class="dark:hidden" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      <svg class="hidden dark:block" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    </button>

    {{-- Notifications --}}
    <div class="relative" x-data="{ open: false }">
      <button @click="open = !open" class="relative flex items-center justify-center w-9 h-9 rounded-xl transition-all"
        style="background:var(--bg-surface);border:1px solid var(--border-color);color:var(--text-secondary);">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
        @if(($notifCount ?? 0) > 0)
          <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full" style="background:var(--accent);border:1.5px solid var(--bg-base);"></span>
        @endif
      </button>
      <div x-show="open" @click.outside="open = false" x-transition
        class="absolute right-0 top-12 w-80 rounded-2xl overflow-hidden z-50"
        style="background:var(--bg-surface);border:1px solid var(--border-color);box-shadow:var(--shadow-lg);">
        <div class="px-4 py-3" style="border-bottom:1px solid var(--border-color);">
          <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--text-primary);">Notifications</span>
        </div>
        <div class="py-2 px-2">
          <div class="text-center py-8" style="color:var(--text-muted);font-size:13px;">No new notifications</div>
        </div>
      </div>
    </div>

    {{-- User menu --}}
    <div class="relative" x-data="{ open: false }">
      @php $uname = auth()->user()->name ?? 'Admin'; $uinitials = implode('', array_map(fn($p) => strtoupper($p[0]), array_slice(explode(' ',$uname),0,2))); @endphp
      <button @click="open = !open" class="flex items-center gap-2 px-2 py-1.5 rounded-xl transition-all"
        style="background:var(--bg-surface);border:1px solid var(--border-color);">
        <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,var(--accent),var(--accent-gold));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;font-size:11px;color:white;">{{ $uinitials }}</div>
        <span class="hidden md:block text-xs font-medium" style="color:var(--text-primary);max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $uname }}</span>
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted);"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div x-show="open" @click.outside="open = false" x-transition
        class="absolute right-0 top-12 w-56 rounded-2xl overflow-hidden z-50"
        style="background:var(--bg-surface);border:1px solid var(--border-color);box-shadow:var(--shadow-lg);">
        <div class="px-4 py-3" style="border-bottom:1px solid var(--border-color);">
          <div style="font-weight:600;font-size:13px;color:var(--text-primary);">{{ $uname }}</div>
          <div style="font-size:11px;color:var(--text-muted);">{{ auth()->user()->email ?? '' }}</div>
        </div>
        <div class="py-1.5 px-2">
          <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all" style="color:var(--text-secondary);" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='transparent'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg Profile
          </a>
          <a href="{{ route('settings.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all" style="color:var(--text-secondary);" onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='transparent'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2"/></svg Settings
          </a>
          <div style="height:1px;background:var(--border-color);margin:6px 0;"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all text-left" style="color:var(--accent);" onmouseover="this.style.background='rgba(212,80,30,0.07)'" onmouseout="this.style.background='transparent'">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg> Sign Out
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</header>
