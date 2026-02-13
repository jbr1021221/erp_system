<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Prevent FOUC: Apply theme before page renders -->
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
                
                if (theme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <title>@yield('title', config('app.name', 'ERP System'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Dynamic Font Size Variables -->
        <style>
            :root {
                {!! $fontSizeCss ?? '' !!}
                {!! $primaryColorCss ?? '' !!}
            }
        </style>
        

        <style>
            [x-cloak] { display: none !important; }
            body { 
                font-family: 'Outfit', sans-serif;
                background-color: rgb(var(--bg-primary));
                color: rgb(var(--text-secondary));
                font-size: var(--font-size-base, 1rem);
            }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none;  scrollbar-width: none; }
            svg { flex-shrink: 0; }
            
            /* Apply font sizes to common elements */
            .text-sm { font-size: var(--font-size-sm, 0.875rem) !important; }
            .text-base { font-size: var(--font-size-base, 1rem) !important; }
            .text-lg { font-size: var(--font-size-lg, 1.125rem) !important; }
            .text-xl { font-size: var(--font-size-xl, 1.25rem) !important; }
            .text-2xl { font-size: var(--font-size-2xl, 1.5rem) !important; }
            .text-3xl { font-size: var(--font-size-3xl, 1.875rem) !important; }
            .text-4xl { font-size: var(--font-size-4xl, 2.25rem) !important; }
        </style>
        
        @stack('styles')
    </head>
    <body class="h-full font-sans antialiased">
        
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">
            
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 z-40 md:hidden" x-cloak></div>

            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto md:flex md:w-64 md:flex-col shadow-xl" style="background-color: rgb(var(--bg-sidebar)); color: rgb(var(--text-inverse));">
                <!-- Sidebar Header -->
                <div class="flex h-16 shrink-0 items-center justify-center px-4 shadow-sm" style="background-color: rgb(var(--bg-sidebar));">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white font-bold shadow-lg shadow-indigo-500/30">
                            E
                        </div>
                        <span class="text-2xl font-bold tracking-tight" style="color: rgb(var(--text-inverse));">ERP System</span>
                    </a>
                </div>

                <!-- Sidebar Nav -->
                <div class="flex flex-1 flex-col overflow-y-auto px-3 py-4 scrollbar-hide">
                    <nav class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                            <svg class="{{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <div class="pt-4 pb-2">
                            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Academic</p>
                        </div>

                        <!-- Students -->
                        @can('student-list')
                        <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                            <svg class="{{ request()->routeIs('students.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                            Students
                        </a>
                        @endcan

                        <!-- Teachers -->
                        @can('teacher-list')
                        <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                            <svg class="{{ request()->routeIs('teachers.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Teachers
                        </a>
                        @endcan

                        <!-- Classes -->
                        @can('class-list')
                        <a href="{{ route('classes.index') }}" class="{{ request()->routeIs('classes.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                            <svg class="{{ request()->routeIs('classes.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Classes
                        </a>
                        @endcan

                        <div class="pt-4 pb-2">
                            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Finance</p>
                        </div>

                        <!-- Payments -->
                        @can('payment-list')
                        <a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                            <svg class="{{ request()->routeIs('payments.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Income / Fees
                        </a>
                        @endcan

                        <!-- Fee Structure -->
                        @can('fee-list')
                        <a href="{{ route('fee-structures.index') }}" class="{{ request()->routeIs('fee-structures.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200">
                             <svg class="{{ request()->routeIs('fee-structures.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5 flex-shrink-0 transition-colors" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                            </svg>
                            Fee Structure
                        </a>
                        @endcan

                        <!-- Expenses -->
                         @can('expense-list')
                        <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                             <svg class="{{ request()->routeIs('expenses.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                            </svg>
                            Expenses
                        </a>
                        @endcan

                        <div class="pt-4 pb-2">
                            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Administration</p>
                        </div>

                         @can('user-list')
                        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                            <svg class="{{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>
                        @endcan
                        
                        @can('role-list')
                        <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                             <svg class="{{ request()->routeIs('roles.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Roles & Perms
                        </a>
                        @endcan
                        @can('setting-list')
                        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} group flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200">
                             <svg class="{{ request()->routeIs('settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                        @endcan
                    </nav>
                </div>
                
                 <!-- Sidebar Footer -->
                <div class="p-6" style="border-top: 1px solid rgb(var(--border-tertiary)); background-color: rgb(var(--bg-sidebar));">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="group flex w-full items-center gap-3 rounded-xl px-2 py-2 text-sm font-medium transition-all" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-sidebar-hover))'; this.style.color='rgb(var(--text-inverse))';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgb(var(--text-tertiary))';">
                             <svg class="h-5 w-5 transition-colors" style="color: rgb(var(--text-tertiary));" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex flex-1 flex-col overflow-hidden" style="background-color: rgb(var(--bg-primary));">
                <!-- Top Navbar -->
                <header class="flex h-16 w-full items-center justify-between px-4 shadow-sm backdrop-blur-md sticky top-0 z-30" style="border-bottom: 1px solid rgb(var(--border-primary)); background-color: rgba(var(--bg-elevated), 0.8);">
                    <div class="flex items-center gap-6">
                        <button @click="sidebarOpen = true" class="focus:outline-none md:hidden transition-colors" style="color: rgb(var(--text-tertiary));" onmouseover="this.style.color='rgb(var(--accent-primary))';" onmouseout="this.style.color='rgb(var(--text-tertiary));';">
                            <svg class="h-6 w-6" fill="none" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-2xl font-bold tracking-tight" style="color: rgb(var(--text-primary));">
                            @yield('header_title', 'ERP System') 
                        </h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Theme Toggle Button -->
                        <button onclick="toggleTheme()" class="flex items-center justify-center h-10 w-10 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary)); color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))';" onmouseout="this.style.backgroundColor='rgb(var(--bg-elevated))';" title="Toggle Theme">
                            <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 rounded-full p-1 pr-3 transition-all focus:ring-2 focus:outline-none" style="border: 1px solid rgb(var(--border-primary)); background-color: rgb(var(--bg-elevated)); focus:ring-color: rgb(var(--accent-primary));" onmouseover="this.style.backgroundColor='rgb(var(--bg-secondary))';" onmouseout="this.style.backgroundColor='rgb(var(--bg-elevated))';">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 font-bold border border-indigo-200">
                                     {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden text-left md:block">
                                    <p class="text-xs font-semibold leading-none" style="color: rgb(var(--text-primary));">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] leading-none mt-0.5" style="color: rgb(var(--text-tertiary));">{{ Auth::user()->getRoleNames()->first() }}</p>
                                </div>
                                <svg class="h-5 w-5" style="color: rgb(var(--text-tertiary));" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl py-1 shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="background-color: rgb(var(--bg-elevated));">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm transition-colors" style="color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--accent-light))'; this.style.color='rgb(var(--accent-primary))';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgb(var(--text-secondary))';" >Your Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm transition-colors" style="color: rgb(var(--text-secondary));" onmouseover="this.style.backgroundColor='rgb(var(--error-light))'; this.style.color='rgb(var(--error))';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='rgb(var(--text-secondary))';">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-6 md:p-8">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 rounded-xl p-6 shadow-sm flex items-start animate-fade-in" style="background-color: rgb(var(--success-light)); border: 1px solid rgba(var(--success), 0.2);">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5" style="color: rgb(var(--success));" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium" style="color: rgb(var(--success));">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="ml-auto" style="color: rgb(var(--success));">
                                <svg class="h-5 w-5" width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>