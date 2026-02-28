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
<body class="antialiased bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-50" x-data="{ sidebarOpen: false }">

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

  {{-- MOBILE OVERLAY BACKDROP --}}
  <div x-show="sidebarOpen" x-transition.opacity 
       @click="sidebarOpen = false"
       class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" style="display: none;"></div>

  {{-- L E F T   S I D E B A R --}}
  <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-[260px]'" 
         class="fixed inset-y-0 left-0 z-50 w-[260px] bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700/50 transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col">
    
    {{-- Top: Logo Area --}}
    <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-200 dark:border-slate-700/50 shrink-0">
      <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600 text-white font-bold text-lg" style="font-family:'Outfit',sans-serif;">E</div>
      <div>
        <div class="font-semibold text-slate-800 dark:text-white text-sm" style="font-family:'Outfit',sans-serif;">EduERP</div>
        <div class="text-[11px] text-slate-400">Institution Suite</div>
      </div>
    </div>

    {{-- Middle: Navigation Groups --}}
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6" style="scrollbar-width: none;">
      
      {{-- OVERVIEW --}}
      <div>
        <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 px-3 mb-1">Overview</div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('dashboard') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
          Dashboard
        </a>
      </div>

      {{-- ACADEMICS --}}
      <div>
        <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 px-3 mb-1">Academics</div>
        <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('students.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('students.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
          Students
        </a>
        <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('teachers.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('teachers.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Teachers
        </a>
        <a href="{{ route('classes.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('classes.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('classes.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
          Classes
        </a>
      </div>

      {{-- FINANCE --}}
      <div>
        <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 px-3 mb-1">Finance</div>
        <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('payments.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('payments.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          Income / Fees
        </a>
        <a href="{{ route('fee-structures.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('fee-structures.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('fee-structures.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
          Fee Structure
        </a>
        <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('expenses.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('expenses.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 22h14a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v4"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M2 15h10"></path><path d="M9 18l3-3-3-3"></path></svg>
          Expenses
        </a>
        <a href="{{ route('accounts.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('accounts.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('accounts.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path></svg>
          Accounts
        </a>
      </div>

      {{-- SYSTEM --}}
      <div>
        <div class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 px-3 mb-1">System</div>
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('users.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('users.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          Users
        </a>
        <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('roles.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('roles.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          Roles & Perms
        </a>
        <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('reports.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('reports.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg>
          Reports
        </a>
        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium mt-1 {{ request()->routeIs('settings.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 relative before:content-[\'\'] before:absolute before:left-0 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-600 before:rounded-full' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
          <svg class="{{ request()->routeIs('settings.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          Settings
        </a>
      </div>
      
    </div>

    {{-- Bottom: User Area --}}
    <div class="mt-auto p-3 pb-4 border-t border-slate-200 dark:border-slate-700/50 bg-white dark:bg-slate-900 shrink-0">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-600 to-emerald-400 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm border border-emerald-500/50">
          {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-medium text-slate-700 dark:text-slate-200 truncate">{{ Auth::user()->name }}</div>
          <div class="text-[11px] font-medium text-emerald-600 dark:text-emerald-500 truncate">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Logout">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
          </button>
        </form>
      </div>
    </div>
  </aside>


  {{-- TOP NAVBAR --}}
  <header class="fixed top-0 left-0 lg:left-[260px] right-0 z-40 h-[56px] bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-slate-200 dark:border-slate-700/50 flex items-center px-4 lg:px-6 justify-between transition-all duration-300">
    
    {{-- Left side: Mobile Toggle & Page Title --}}
    <div class="flex items-center gap-4">
      <button @click="sidebarOpen = true" class="lg:hidden p-1.5 -ml-1.5 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 rounded-lg">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>

      <h1 class="text-base font-semibold text-slate-800 dark:text-slate-100 truncate md:text-lg">
        @yield('page-title', 'Dashboard')
      </h1>
    </div>

    {{-- Right side: Actions --}}
    <div class="flex items-center gap-2 lg:gap-3">
      
      {{-- Search --}}
      <div class="hidden sm:block relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <input type="text" placeholder="Search..." class="pl-9 pr-3 py-1.5 text-sm rounded-full bg-slate-100/80 dark:bg-slate-800/80 border-transparent text-slate-800 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 w-44 focus:w-64 transition-all duration-300">
      </div>

      {{-- Quick Actions (+ New) --}}
      <div x-data="{ quickOpen: false }" class="relative hidden sm:block">
        <button @click="quickOpen = !quickOpen" @click.away="quickOpen = false" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium px-3.5 py-1.5 flex items-center gap-1.5 transition-colors shadow-sm">
          <span>+ New</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div x-show="quickOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 shadow-xl border border-slate-100 dark:border-slate-700 rounded-xl py-1.5 z-50" style="display: none;">
          <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400">Add Student</a>
          <a href="{{ route('payments.create') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400">Collect Fee</a>
          <div class="h-px bg-slate-100 dark:bg-slate-700/50 my-1.5"></div>
          <a href="{{ route('expenses.create') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400">Record Expense</a>
        </div>
      </div>

      {{-- Dark Mode Toggle --}}
      <button onclick="toggleTheme()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors">
        <svg class="dark:hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="hidden dark:block" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>

      {{-- Notifications --}}
      <div x-data="{ notifOpen: false }" class="relative">
          <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors relative">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <div class="absolute top-[3px] right-[4px] w-2 h-2 bg-rose-500 rounded-full border-2 border-white dark:border-slate-900"></div>
          </button>
          <div x-show="notifOpen" x-transition class="absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 shadow-xl border border-slate-200 dark:border-slate-700 rounded-xl py-2 z-50 text-center text-sm text-slate-500 dark:text-slate-400" style="display: none;">
             No new notifications.
          </div>
      </div>
      
      <div class="hidden sm:block w-px h-6 bg-slate-200 dark:bg-slate-700/50 mx-1"></div>

      {{-- User Profile Dropdown --}}
      <div x-data="{ profileOpen: false }" class="relative flex items-center gap-2 cursor-pointer p-1 -mr-1 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors" @click="profileOpen = !profileOpen" @click.away="profileOpen = false">
        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-emerald-400 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
           {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="hidden md:flex items-center gap-1.5">
          <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div x-show="profileOpen" x-transition class="absolute right-0 top-12 mt-1 w-56 bg-white dark:bg-slate-800 shadow-xl border border-slate-100 dark:border-slate-700 rounded-xl py-1.5 z-50" style="display: none;">
          <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700/50 mb-1 lg:hidden">
             <div class="font-medium text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</div>
             <div class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
          </div>
          <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400">
             <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
             Profile Settings
          </a>
          <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-emerald-600 dark:hover:text-emerald-400">
             <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
             System Settings
          </a>
          <div class="h-px bg-slate-100 dark:bg-slate-700/50 my-1.5"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 dark:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 flex items-center gap-2">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
              Sign Out
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  {{-- SECONDARY SUB-NAV BAR (Under Top Box) --}}
  <div class="fixed top-[56px] left-0 lg:left-[260px] right-0 z-30 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 h-[40px] flex items-center justify-between px-4 lg:px-6 overflow-x-auto transition-all duration-300" style="scrollbar-width: none;">
    <div class="flex items-center gap-1 min-w-max">
      @hasSection('subnav')
        @yield('subnav')
      @else
        <a href="{{ route('dashboard') }}" class="text-sm px-4 h-[40px] flex items-center border-b-2 border-emerald-600 text-emerald-600 font-medium">Overview</a>
      @endif
    </div>
    <div class="hidden sm:block text-[11px] font-medium text-slate-400 whitespace-nowrap ml-4">
      @hasSection('breadcrumb')
        @yield('breadcrumb')
      @else
        EduERP / Dashboard
      @endif
    </div>
  </div>


  {{-- MAIN CONTENT --}}
  <main class="lg:ml-[260px] pt-[96px] min-h-screen transition-all duration-300 flex flex-col">
    <div class="flex-1 w-full max-w-full mx-auto px-4 md:px-6 lg:px-8 py-6">
      
      @yield('content')

    </div>
    
    <footer class="mt-auto px-4 md:px-6 lg:px-8 py-4 border-t border-slate-200 dark:border-slate-800 text-center">
      <span class="text-[11px] text-slate-500 font-medium tracking-wide uppercase" style="font-family:'Plus Jakarta Sans',sans-serif;">
        © {{ date('Y') }} EduERP · Educational Institution Suite · v3.0
      </span>
    </footer>
  </main>


  {{-- Toast container --}}
  <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3" style="pointer-events:none; z-index:9999;"></div>

  <script src="{{ asset('js/app-ui.js') }}"></script>
  @stack('scripts')
</body>
</html>
