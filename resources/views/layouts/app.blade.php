<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name')) — EduERP</title>

  <script>
    (function(){
      const t = localStorage.getItem('theme');
      const d = window.matchMedia('(prefers-color-scheme: dark)').matches;
      if (t === 'dark' || (!t && d)) document.documentElement.classList.add('dark');
    })();
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  @stack('styles')
</head>
<body class="antialiased bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-50" x-data="{ mobileNav: false }">

  {{-- Flash data for toast --}}
  @if(session('success'))
    <div id="flash-data" data-type="success" data-message="{{ session('success') }}" class="hidden"></div>
  @elseif(session('error'))
    <div id="flash-data" data-type="error" data-message="{{ session('error') }}" class="hidden"></div>
  @elseif(session('warning'))
    <div id="flash-data" data-type="warning" data-message="{{ session('warning') }}" class="hidden"></div>
  @elseif(session('info'))
    <div id="flash-data" data-type="info" data-message="{{ session('info') }}" class="hidden"></div>
  @endif

  {{-- PRIMARY NAVBAR --}}
  <header class="sticky top-0 z-50 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 h-[60px] flex items-center px-4 lg:px-6">
    
    {{-- Left: Mobile Hamburger & Logo --}}
    <div class="flex items-center gap-3 w-1/4 lg:w-auto">
      <button @click="mobileNav = !mobileNav" class="lg:hidden p-1.5 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 rounded-md">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>

      <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-md flex items-center justify-center bg-emerald-600 text-white font-bold" style="font-family:'Outfit',sans-serif;">E</div>
        <div class="flex items-baseline gap-2">
          <span class="font-semibold text-slate-800 dark:text-slate-100 text-lg hidden sm:block" style="font-family:'Outfit',sans-serif;">EduERP</span>
          <span class="text-xs text-slate-400 font-medium hidden lg:block">Institution Suite</span>
        </div>
      </a>
    </div>

    {{-- Center-Left: Main Nav (Desktop) --}}
    <nav class="hidden lg:flex items-center ml-8 gap-1 flex-1">
      <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-emerald-600 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white' }}">
        Overview
      </a>

      {{-- Academics Dropdown --}}
      <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
        <button class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium {{ request()->routeIs('students.*', 'teachers.*', 'classes.*') ? 'text-emerald-600 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white'}}">
          Academics
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="open" x-transition.opacity duration.150ms class="absolute left-0 mt-0 w-48 pt-1" style="display: none;">
          <div class="bg-white dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-slate-700 rounded-lg py-1">
            <a href="{{ route('students.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Students</a>
            <a href="{{ route('teachers.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Teachers</a>
            <a href="{{ route('classes.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Classes</a>
          </div>
        </div>
      </div>

      {{-- Finance Dropdown --}}
      <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
        <button class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium {{ request()->routeIs('payments.*', 'fee-structures.*', 'expenses.*', 'accounts.*') ? 'text-emerald-600 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white' }}">
          Finance
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="open" x-transition.opacity duration.150ms class="absolute left-0 mt-0 w-48 pt-1" style="display: none;">
          <div class="bg-white dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-slate-700 rounded-lg py-1">
            <a href="{{ route('payments.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Income / Fees</a>
            <a href="{{ route('fee-structures.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Fee Structure</a>
            <a href="{{ route('expenses.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Expenses</a>
            <a href="{{ route('accounts.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Accounts</a>
          </div>
        </div>
      </div>

      {{-- System Dropdown --}}
      <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
        <button class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium {{ request()->routeIs('users.*', 'roles.*', 'reports.*', 'settings.*') ? 'text-emerald-600 font-semibold' : 'text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white' }}">
          System
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="open" x-transition.opacity duration.150ms class="absolute left-0 mt-0 w-48 pt-1" style="display: none;">
          <div class="bg-white dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-slate-700 rounded-lg py-1">
            <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Users</a>
            <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Roles & Perms</a>
            <a href="{{ route('reports.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Reports</a>
            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Settings</a>
          </div>
        </div>
      </div>
    </nav>

    {{-- Right: Actions & User --}}
    <div class="flex items-center justify-end gap-3 w-3/4 lg:w-auto">
      
      {{-- Search --}}
      <div class="hidden sm:block relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <input type="text" placeholder="Search..." class="pl-9 pr-3 py-1.5 text-sm rounded-full bg-slate-100 border-transparent dark:bg-slate-800 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 w-32 focus:w-48 transition-all duration-300 disabled:opacity-50">
      </div>

      {{-- Quick Actions (+ New) --}}
      <div x-data="{ quickOpen: false }" class="relative hidden sm:block">
        <button @click="quickOpen = !quickOpen" @click.away="quickOpen = false" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium px-4 py-2 flex items-center gap-1 transition-colors">
          <span>+ New</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="quickOpen" x-transition class="absolute right-0 mt-2 w-40 bg-white dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-slate-700 rounded-lg py-1" style="display: none;">
          <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Add Student</a>
          <a href="{{ route('payments.create') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Collect Fee</a>
        </div>
      </div>

      {{-- Dark Mode Toggle --}}
      <button onclick="toggleTheme()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors">
        <svg class="dark:hidden" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="hidden dark:block" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>

      {{-- Notifications --}}
      <button class="w-8 h-8 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors relative">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-900"></div>
      </button>

      {{-- User Profile --}}
      <div x-data="{ profileOpen: false }" class="relative flex items-center gap-2">
        <div class="hidden md:flex flex-col items-end mr-1">
          <span class="text-[10px] text-slate-400">{{ now()->format('D, M j, Y') }}</span>
          <span class="text-sm font-semibold text-slate-800 dark:text-slate-100 leading-tight">{{ Auth::user()->name }}</span>
        </div>
        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="focus:outline-none">
          <div class="w-9 h-9 flex items-center justify-center transition-all bg-emerald-600 rounded-full border-2 border-white dark:border-slate-800 shadow-sm text-white font-bold text-sm">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
          </div>
        </button>
        <div x-show="profileOpen" x-transition class="absolute right-0 top-12 mt-1 w-48 bg-white dark:bg-slate-800 shadow-lg border border-slate-100 dark:border-slate-700 rounded-lg py-1" style="display: none;">
          <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 lg:hidden">
            <div class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</div>
            <div class="text-xs text-slate-500">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</div>
          </div>
          <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">Profile Settings</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </header>

  {{-- MOBILE NAV DRAWER --}}
  <div x-show="mobileNav" x-transition.opacity class="lg:hidden absolute top-[60px] inset-x-0 z-40 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 shadow-md p-4 flex flex-col gap-4" style="display: none;">
      <a href="{{ route('dashboard') }}" class="block font-medium text-slate-800 dark:text-slate-100">Overview</a>
      <div class="font-medium text-slate-800 dark:text-slate-100">Academics
        <div class="pl-4 mt-2 flex flex-col gap-2">
          <a href="{{ route('students.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Students</a>
          <a href="{{ route('teachers.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Teachers</a>
          <a href="{{ route('classes.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Classes</a>
        </div>
      </div>
      <div class="font-medium text-slate-800 dark:text-slate-100">Finance
        <div class="pl-4 mt-2 flex flex-col gap-2">
          <a href="{{ route('payments.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Income / Fees</a>
          <a href="{{ route('fee-structures.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Fee Structure</a>
          <a href="{{ route('expenses.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Expenses</a>
          <a href="{{ route('accounts.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Accounts</a>
        </div>
      </div>
      <div class="font-medium text-slate-800 dark:text-slate-100">System
        <div class="pl-4 mt-2 flex flex-col gap-2">
          <a href="{{ route('users.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Users</a>
          <a href="{{ route('roles.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Roles & Perms</a>
          <a href="{{ route('reports.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Reports</a>
          <a href="{{ route('settings.index') }}" class="text-sm text-slate-600 dark:text-slate-400">Settings</a>
        </div>
      </div>
      <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-2">
         <a href="{{ route('students.create') }}" class="text-sm text-emerald-600 font-medium">+ Add Student</a>
         <a href="{{ route('payments.create') }}" class="text-sm text-emerald-600 font-medium">+ Collect Fee</a>
      </div>
  </div>

  {{-- SECONDARY SUB-NAV BAR --}}
  <div class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-700 h-[44px] flex items-center justify-between px-4 lg:px-6 overflow-x-auto" style="scrollbar-width: none;">
    <div class="flex items-center gap-1 min-w-max">
      @hasSection('subnav')
        @yield('subnav')
      @else
        <a href="{{ route('dashboard') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 border-emerald-600 text-emerald-600 font-medium">Overview</a>
        <a href="#" class="text-sm px-4 h-[44px] flex items-center border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">Analytics</a>
        <a href="#" class="text-sm px-4 h-[44px] flex items-center border-b-2 border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">Recent Activity</a>
      @endif
    </div>
    <div class="hidden sm:block text-xs text-slate-400 whitespace-nowrap ml-4">
      @hasSection('breadcrumb')
        @yield('breadcrumb')
      @else
        EduERP / Dashboard
      @endif
    </div>
  </div>

  {{-- MAIN CONTENT --}}
  <main class="max-w-screen-xl mx-auto px-4 md:px-8 py-8">
    @yield('content')
  </main>

  <footer class="max-w-screen-xl mx-auto py-4 px-4 md:px-8 border-t text-center dark:border-slate-800" style="border-color:var(--border-color);">
    <span class="text-[11px] text-slate-500 font-medium" style="font-family:'Plus Jakarta Sans',sans-serif;">
      © {{ date('Y') }} EduERP · Educational Institution Suite · v3.0
    </span>
  </footer>

  {{-- Toast container --}}
  <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3" style="pointer-events:none; z-index:9999;"></div>

  <script>
    function toggleTheme() {
      const html = document.documentElement;
      const isDark = html.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
  </script>
  <script src="{{ asset('js/app-ui.js') }}"></script>
  @stack('scripts')
</body>
</html>