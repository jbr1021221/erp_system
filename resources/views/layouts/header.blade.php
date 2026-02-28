{{--
  layouts/partials/topbar.blade.php
  Modern EduERP Topbar — replaces Bootstrap header + old nav entirely
--}}

<header class="sticky top-0 z-40 flex items-center gap-3 px-6 py-3"
        style="background: rgba(var(--bg-base-rgb, 245,240,232), 0.85);
               backdrop-filter: blur(12px);
               -webkit-backdrop-filter: blur(12px);
               border-bottom: 1px solid var(--border-color);
               height: 60px;">

  {{-- Mobile hamburger --}}
  <button @click="sidebarOpen = !sidebarOpen"
          class="lg:hidden flex items-center justify-center w-9 h-9 rounded-xl transition-all duration-200 flex-shrink-0"
          style="border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-secondary);">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
  </button>

  {{-- Page title (dynamic via @section or fallback) --}}
  <div class="flex-1 min-w-0">
    <h1 style="font-family:'Syne',sans-serif; font-weight:700; font-size:18px; color:var(--text-primary); line-height:1.2;" class="truncate">
      @hasSection('page-title')
        @yield('page-title')
      @else
        @yield('title', 'Dashboard')
      @endif
    </h1>
    @hasSection('breadcrumb')
    <div style="font-size:12px; color:var(--text-muted); margin-top:1px;">
      @yield('breadcrumb')
    </div>
    @endif
  </div>

  {{-- Right side controls --}}
  <div class="flex items-center gap-2 flex-shrink-0">

    {{-- Date --}}
    <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl"
         style="background:var(--bg-surface); border:1px solid var(--border-color); font-size:12px; color:var(--text-muted);">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      <span id="topbar-date">{{ now()->format('D, M j, Y') }}</span>
    </div>

    {{-- Dark mode toggle --}}
    <button onclick="toggleTheme()"
            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200"
            style="background:var(--bg-surface); border:1px solid var(--border-color); color:var(--text-muted);"
            title="Toggle dark mode">
      {{-- Sun icon (shown in dark mode) --}}
      <svg class="dark:hidden" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
      </svg>
      {{-- Moon icon --}}
      <svg class="hidden dark:block" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
      </svg>
    </button>

    {{-- Notifications --}}
    <button class="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200"
            style="background:var(--bg-surface); border:1px solid var(--border-color); color:var(--text-muted);"
            title="Notifications">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
    </button>

    {{-- User dropdown --}}
    <div x-data="{ userOpen: false }" class="relative">
      <button @click="userOpen = !userOpen"
              class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl transition-all duration-200"
              style="background:var(--bg-surface); border:1px solid var(--border-color);">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
             style="background: linear-gradient(135deg, var(--accent), var(--accent-hover)); font-family:'Syne',sans-serif;">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="hidden md:block text-left">
          <div style="font-size:12px; font-weight:600; color:var(--text-primary); line-height:1.2;">{{ Auth::user()->name }}</div>
          <div style="font-size:10px; color:var(--text-muted); line-height:1.2;">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</div>
        </div>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round" style="color:var(--text-muted);" class="ml-1">
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </button>

      {{-- Dropdown menu --}}
      <div x-show="userOpen"
           x-transition:enter="transition ease-out duration-150"
           x-transition:enter-start="opacity-0 scale-95 translate-y-1"
           x-transition:enter-end="opacity-100 scale-100 translate-y-0"
           x-transition:leave="transition ease-in duration-100"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0 scale-95"
           @click.away="userOpen = false"
           class="absolute right-0 mt-2 w-52 rounded-2xl overflow-hidden"
           style="background:var(--bg-surface); border:1px solid var(--border-color); box-shadow:var(--shadow-lg); top:100%;">

        <div class="px-4 py-3" style="border-bottom:1px solid var(--border-color);">
          <div style="font-size:12px; font-weight:600; color:var(--text-primary);">{{ Auth::user()->name }}</div>
          <div style="font-size:11px; color:var(--text-muted);">{{ Auth::user()->email }}</div>
        </div>

        <div class="py-1.5 px-2">
          <a href="{{ route('profile.edit') }}"
             class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-150"
             style="color:var(--text-secondary);"
             onmouseover="this.style.background='var(--bg-surface-2)';this.style.color='var(--text-primary)'"
             onmouseout="this.style.background='transparent';this.style.color='var(--text-secondary)'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            Profile
          </a>
        </div>

        <div class="py-1.5 px-2" style="border-top:1px solid var(--border-color);">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-150 text-left"
                    style="color:var(--accent);"
                    onmouseover="this.style.background='rgba(212,80,30,0.08)'"
                    onmouseout="this.style.background='transparent'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              Sign Out
            </button>
          </form>
        </div>

      </div>
    </div>

  </div>
</header>

<script>
  function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  }
</script>