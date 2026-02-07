<nav x-data="{ open: false }" class="bg-slate-50 border-b border-slate-200 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-opacity-90">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold shadow-lg">
                            E
                        </div>
                        <span class="font-bold text-2xl bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-600">ERP System</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-slate-600 hover:text-slate-900 transition-colors duration-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @can('student-list')
                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')" class="text-slate-600 hover:text-slate-900 transition-colors duration-200">
                        {{ __('Students') }}
                    </x-nav-link>
                    @endcan

                    @can('payment-list')
                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-slate-600 hover:text-slate-900 transition-colors duration-200">
                        {{ __('Payments') }}
                    </x-nav-link>
                    @endcan

                    @can('class-list')
                    <x-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" class="text-slate-600 hover:text-slate-900 transition-colors duration-200">
                        {{ __('Classes') }}
                    </x-nav-link>
                    @endcan

                    <!-- More dropdown for other modules -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 hover:border-slate-300 focus:outline-none focus:text-slate-800 focus:border-slate-300 transition duration-150 ease-in-out">
                                    <div>More</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @can('expense-list')
                                <x-dropdown-link :href="route('expenses.index')">
                                    {{ __('Expenses') }}
                                </x-dropdown-link>
                                @endcan
                                
                                @can('user-list')
                                <x-dropdown-link :href="route('users.index')">
                                    {{ __('Users') }}
                                </x-dropdown-link>
                                @endcan

                                @can('role-list')
                                <x-dropdown-link :href="route('roles.index')">
                                    {{ __('Roles & Permissions') }}
                                </x-dropdown-link>
                                @endcan
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300 focus:outline-none focus:text-slate-700 focus:border-slate-300 transition duration-150 ease-in-out">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-300 flex items-center justify-center text-slate-600 font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-500">{{ Auth::user()->getRoleNames()->first() }}</div>
                                </div>
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-slate-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="border-t border-slate-200"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-200 focus:outline-none focus:bg-slate-200 focus:text-slate-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-50 border-t border-slate-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @can('student-list')
            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                {{ __('Students') }}
            </x-responsive-nav-link>
            @endcan

            @can('payment-list')
            <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                {{ __('Payments') }}
            </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                <div class="font-medium text-lg text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
