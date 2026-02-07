<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl leading-tight" style="color: rgb(var(--text-primary));">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Banner - Premium Edition -->
            <div class="relative overflow-hidden rounded-2xl animate-fade-in" style="background: rgb(var(--bg-elevated)); border: 2px solid rgb(var(--border-primary)); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                <!-- Decorative Top Border -->
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl" style="background: linear-gradient(90deg, rgb(var(--accent-primary)), rgb(var(--accent-hover)));"></div>
                
                <!-- Decorative Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-0 right-0 w-64 h-64 rounded-full" style="background: radial-gradient(circle, rgb(var(--accent-primary)) 0%, transparent 70%); transform: translate(30%, -30%);"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full" style="background: radial-gradient(circle, rgb(var(--accent-primary)) 0%, transparent 70%); transform: translate(-40%, 40%);"></div>
                </div>
                
                <!-- Content -->
                <div class="relative p-8 md:p-10 pt-9">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover))); box-shadow: 0 4px 12px rgba(var(--accent-primary), 0.3);">
                                    <svg class="w-6 h-6" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-widest" style="color: rgb(var(--text-tertiary));">Dashboard Overview</span>
                            </div>
                            <h1 class="text-3xl md:text-4xl font-bold mb-2 leading-tight" style="color: rgb(var(--text-primary));">
                                Welcome back, {{ Auth::user()->name }}! 👋
                            </h1>
                            <p class="text-sm md:text-base max-w-2xl" style="color: rgb(var(--text-secondary));">
                                Here's what's happening with your institution today. Monitor key metrics and take quick actions.
                            </p>
                            
                            <!-- Quick Stats in Banner -->
                            <div class="mt-6 flex flex-wrap gap-6">
                                <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                                    <svg class="w-4 h-4" style="color: rgb(var(--accent-primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-semibold" style="color: rgb(var(--text-primary));">{{ date('l, F j, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                                    <svg class="w-4 h-4" style="color: rgb(var(--accent-primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-sm font-semibold" style="color: rgb(var(--text-primary));">{{ Auth::user()->getRoleNames()->first() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Icon -->
                        <div class="hidden lg:block">
                            <div class="relative">
                                <div class="absolute inset-0 rounded-2xl opacity-10" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover))); filter: blur(20px);"></div>
                                <div class="relative w-32 h-32 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, rgb(var(--accent-primary)) 0%, rgb(var(--accent-hover)) 100%); opacity: 0.1;">
                                    <svg class="w-20 h-20" style="color: rgb(var(--accent-primary)); opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Stats Grid - Premium Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Students Card -->
                <div class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s; background: linear-gradient(135deg, rgba(var(--accent-primary), 0.08), rgba(var(--accent-hover), 0.05)); border: 1px solid rgba(var(--accent-primary), 0.2); box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <!-- Hover Effect -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(135deg, rgba(var(--accent-primary), 0.05), rgba(var(--accent-hover), 0.05));"></div>
                    
                    <div class="relative p-6">
                        <!-- Icon and Title -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(var(--accent-primary), 0.1), rgba(var(--accent-hover), 0.1));">
                                <svg class="w-7 h-7" style="color: rgb(var(--accent-primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide mb-1" style="color: rgb(var(--text-tertiary));">Total Students</p>
                                <p class="text-3xl font-bold" style="color: rgb(var(--text-primary));">1,234</p>
                            </div>
                        </div>
                        
                        <!-- Stats Badge -->
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background: rgba(var(--success), 0.1);">
                            <svg class="w-3.5 h-3.5" style="color: rgb(var(--success));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <span class="text-xs font-semibold" style="color: rgb(var(--success));">12% increase</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s; background: linear-gradient(135deg, rgba(var(--success), 0.08), rgba(var(--success), 0.05)); border: 1px solid rgba(var(--success), 0.2); box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(135deg, rgba(var(--success), 0.05), rgba(var(--success), 0.05));"></div>
                    
                    <div class="relative p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(var(--success), 0.1), rgba(var(--success), 0.1));">
                                <svg class="w-7 h-7" style="color: rgb(var(--success));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide mb-1" style="color: rgb(var(--text-tertiary));">Total Revenue</p>
                                <p class="text-3xl font-bold" style="color: rgb(var(--text-primary));">৳5.2M</p>
                            </div>
                        </div>
                        
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background: rgba(var(--success), 0.1);">
                            <svg class="w-3.5 h-3.5" style="color: rgb(var(--success));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <span class="text-xs font-semibold" style="color: rgb(var(--success));">8% increase</span>
                        </div>
                    </div>
                </div>

                <!-- Teachers Card -->
                <div class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s; background: linear-gradient(135deg, rgba(var(--warning), 0.08), rgba(var(--warning), 0.05)); border: 1px solid rgba(var(--warning), 0.2); box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(135deg, rgba(var(--warning), 0.05), rgba(var(--warning), 0.05));"></div>
                    
                    <div class="relative p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(var(--warning), 0.1), rgba(var(--warning), 0.1));">
                                <svg class="w-7 h-7" style="color: rgb(var(--warning));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide mb-1" style="color: rgb(var(--text-tertiary));">Total Teachers</p>
                                <p class="text-3xl font-bold" style="color: rgb(var(--text-primary));">87</p>
                            </div>
                        </div>
                        
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background: rgba(var(--success), 0.1);">
                            <svg class="w-3.5 h-3.5" style="color: rgb(var(--success));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-xs font-semibold" style="color: rgb(var(--success));">3 new this month</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Fees Card -->
                <div class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.4s; background: linear-gradient(135deg, rgba(var(--error), 0.08), rgba(var(--error), 0.05)); border: 1px solid rgba(var(--error), 0.2); box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" style="background: linear-gradient(135deg, rgba(var(--error), 0.05), rgba(var(--error), 0.05));"></div>
                    
                    <div class="relative p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-full flex items-center justify-center transition-transform duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(var(--error), 0.1), rgba(var(--error), 0.1));">
                                <svg class="w-7 h-7" style="color: rgb(var(--error));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide mb-1" style="color: rgb(var(--text-tertiary));">Pending Fees</p>
                                <p class="text-3xl font-bold" style="color: rgb(var(--text-primary));">৳892K</p>
                            </div>
                        </div>
                        
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background: rgba(var(--success), 0.1);">
                            <svg class="w-3.5 h-3.5" style="color: rgb(var(--success));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                            <span class="text-xs font-semibold" style="color: rgb(var(--success));">5% decrease</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Recent Activity - Premium Design -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Quick Actions Card -->
                <div class="group relative overflow-hidden rounded-2xl animate-fade-in" style="animation-delay: 0.5s; background: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary)); box-shadow: var(--shadow-sm);">
                    <!-- Header -->
                    <div class="p-6 pb-4 border-b" style="border-color: rgb(var(--border-primary));">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover)));">
                                    <svg class="w-5 h-5" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold" style="color: rgb(var(--text-primary));">Quick Actions</h3>
                                    <p class="text-xs" style="color: rgb(var(--text-tertiary));">Frequently used tasks</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions Grid -->
                    <div class="p-6 grid grid-cols-2 gap-4">
                        @can('student-create')
                        <a href="{{ route('students.create') }}" class="group/item relative overflow-hidden rounded-xl p-4 transition-all duration-300 hover:scale-105 hover:-translate-y-1" style="background: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                            <div class="absolute inset-0 opacity-0 group-hover/item:opacity-10 transition-opacity" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover)));"></div>
                            <div class="relative flex flex-col items-center text-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover/item:scale-110" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover))); box-shadow: 0 4px 12px -2px rgba(var(--accent-primary), 0.3);">
                                    <svg class="w-6 h-6" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold mb-0.5" style="color: rgb(var(--text-primary));">Add Student</p>
                                    <p class="text-xs" style="color: rgb(var(--text-tertiary));">New admission</p>
                                </div>
                            </div>
                        </a>
                        @endcan

                        @can('payment-create')
                        <a href="{{ route('payments.index') }}" class="group/item relative overflow-hidden rounded-xl p-4 transition-all duration-300 hover:scale-105 hover:-translate-y-1" style="background: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                            <div class="absolute inset-0 opacity-0 group-hover/item:opacity-10 transition-opacity" style="background: linear-gradient(135deg, rgb(var(--success)), rgb(var(--success)));"></div>
                            <div class="relative flex flex-col items-center text-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover/item:scale-110" style="background: linear-gradient(135deg, rgb(var(--success)), rgb(var(--success))); box-shadow: 0 4px 12px -2px rgba(var(--success), 0.3);">
                                    <svg class="w-6 h-6" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold mb-0.5" style="color: rgb(var(--text-primary));">Collect Fee</p>
                                    <p class="text-xs" style="color: rgb(var(--text-tertiary));">Process payment</p>
                                </div>
                            </div>
                        </a>
                        @endcan

                        @can('expense-create')
                        <a href="{{ route('expenses.index') }}" class="group/item relative overflow-hidden rounded-xl p-4 transition-all duration-300 hover:scale-105 hover:-translate-y-1" style="background: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                            <div class="absolute inset-0 opacity-0 group-hover/item:opacity-10 transition-opacity" style="background: linear-gradient(135deg, rgb(var(--error)), rgb(var(--error)));"></div>
                            <div class="relative flex flex-col items-center text-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover/item:scale-110" style="background: linear-gradient(135deg, rgb(var(--error)), rgb(var(--error))); box-shadow: 0 4px 12px -2px rgba(var(--error), 0.3);">
                                    <svg class="w-6 h-6" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold mb-0.5" style="color: rgb(var(--text-primary));">Add Expense</p>
                                    <p class="text-xs" style="color: rgb(var(--text-tertiary));">Record expense</p>
                                </div>
                            </div>
                        </a>
                        @endcan

                        <a href="{{ route('students.index') }}" class="group/item relative overflow-hidden rounded-xl p-4 transition-all duration-300 hover:scale-105 hover:-translate-y-1" style="background: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                            <div class="absolute inset-0 opacity-0 group-hover/item:opacity-10 transition-opacity" style="background: linear-gradient(135deg, rgb(var(--warning)), rgb(var(--warning)));"></div>
                            <div class="relative flex flex-col items-center text-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover/item:scale-110" style="background: linear-gradient(135deg, rgb(var(--warning)), rgb(var(--warning))); box-shadow: 0 4px 12px -2px rgba(var(--warning), 0.3);">
                                    <svg class="w-6 h-6" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold mb-0.5" style="color: rgb(var(--text-primary));">View Reports</p>
                                    <p class="text-xs" style="color: rgb(var(--text-tertiary));">Analytics</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="group relative overflow-hidden rounded-2xl animate-fade-in" style="animation-delay: 0.6s; background: rgb(var(--bg-elevated)); border: 1px solid rgb(var(--border-primary)); box-shadow: var(--shadow-sm);">
                    <!-- Header -->
                    <div class="p-6 pb-4 border-b" style="border-color: rgb(var(--border-primary));">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover)));">
                                    <svg class="w-5 h-5" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold" style="color: rgb(var(--text-primary));">Recent Activity</h3>
                                    <p class="text-xs" style="color: rgb(var(--text-tertiary));">Latest updates</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background: rgb(var(--accent-light)); color: rgb(var(--accent-primary));">Live</span>
                        </div>
                    </div>
                    
                    <!-- Activity List -->
                    <div class="p-6 space-y-4">
                        <div class="group/activity flex items-start gap-4 p-3 rounded-xl transition-all duration-200 hover:scale-[1.02]" style="background: rgb(var(--bg-secondary));">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl flex-shrink-0 transition-all duration-300 group-hover/activity:scale-110" style="background: linear-gradient(135deg, rgb(var(--success)), rgb(var(--success))); box-shadow: 0 4px 12px -2px rgba(var(--success), 0.3);">
                                <svg class="w-5 h-5" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold mb-1" style="color: rgb(var(--text-primary));">Payment received from John Doe</p>
                                <p class="text-xs" style="color: rgb(var(--text-tertiary));">2 minutes ago</p>
                            </div>
                            <span class="badge badge-success flex-shrink-0">৳5,000</span>
                        </div>

                        <div class="group/activity flex items-start gap-4 p-3 rounded-xl transition-all duration-200 hover:scale-[1.02]" style="background: rgb(var(--bg-secondary));">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl flex-shrink-0 transition-all duration-300 group-hover/activity:scale-110" style="background: linear-gradient(135deg, rgb(var(--accent-primary)), rgb(var(--accent-hover))); box-shadow: 0 4px 12px -2px rgba(var(--accent-primary), 0.3);">
                                <svg class="w-5 h-5" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold mb-1" style="color: rgb(var(--text-primary));">New student admitted</p>
                                <p class="text-xs" style="color: rgb(var(--text-tertiary));">1 hour ago</p>
                            </div>
                            <span class="badge badge-primary flex-shrink-0">New</span>
                        </div>

                        <div class="group/activity flex items-start gap-4 p-3 rounded-xl transition-all duration-200 hover:scale-[1.02]" style="background: rgb(var(--bg-secondary));">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl flex-shrink-0 transition-all duration-300 group-hover/activity:scale-110" style="background: linear-gradient(135deg, rgb(var(--error)), rgb(var(--error))); box-shadow: 0 4px 12px -2px rgba(var(--error), 0.3);">
                                <svg class="w-5 h-5" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold mb-1" style="color: rgb(var(--text-primary));">Expense added: Office supplies</p>
                                <p class="text-xs" style="color: rgb(var(--text-tertiary));">3 hours ago</p>
                            </div>
                            <span class="badge badge-error flex-shrink-0">৳2,500</span>
                        </div>

                        <div class="group/activity flex items-start gap-4 p-3 rounded-xl transition-all duration-200 hover:scale-[1.02]" style="background: rgb(var(--bg-secondary));">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl flex-shrink-0 transition-all duration-300 group-hover/activity:scale-110" style="background: linear-gradient(135deg, rgb(var(--warning)), rgb(var(--warning))); box-shadow: 0 4px 12px -2px rgba(var(--warning), 0.3);">
                                <svg class="w-5 h-5" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold mb-1" style="color: rgb(var(--text-primary));">Fee reminder sent to 45 students</p>
                                <p class="text-xs" style="color: rgb(var(--text-tertiary));">5 hours ago</p>
                            </div>
                            <span class="badge badge-warning flex-shrink-0">Reminder</span>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 pb-6">
                        <a href="#" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl font-semibold text-sm transition-all hover:scale-[1.02]" style="background: rgb(var(--bg-secondary)); color: rgb(var(--text-primary)); border: 1px solid rgb(var(--border-primary));">
                            View All Activity
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Theme Demo Section -->
            <div class="card-premium p-8 animate-fade-in" style="animation-delay: 0.7s;">
                <h3 class="text-xl font-bold mb-6" style="color: rgb(var(--text-primary));">🎨 Theme System Demo</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Component Showcase -->
                    <div>
                        <h4 class="text-sm font-bold mb-3 uppercase tracking-wider" style="color: rgb(var(--text-tertiary));">Buttons</h4>
                        <div class="space-y-2">
                            <button class="btn-primary w-full">Primary Button</button>
                            <button class="w-full px-4 py-2 rounded-xl font-semibold transition-all" style="background-color: rgb(var(--bg-secondary)); color: rgb(var(--text-primary)); border: 1px solid rgb(var(--border-primary));">Secondary Button</button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold mb-3 uppercase tracking-wider" style="color: rgb(var(--text-tertiary));">Badges</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="badge badge-primary">Primary</span>
                            <span class="badge badge-success">Success</span>
                            <span class="badge badge-warning">Warning</span>
                            <span class="badge badge-error">Error</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold mb-3 uppercase tracking-wider" style="color: rgb(var(--text-tertiary));">Input</h4>
                        <input type="text" class="input-premium w-full" placeholder="Premium input field...">
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-xl" style="background-color: rgb(var(--bg-secondary)); border: 1px solid rgb(var(--border-primary));">
                    <p class="text-sm" style="color: rgb(var(--text-secondary));">
                        <strong style="color: rgb(var(--text-primary));">💡 Tip:</strong> Click the theme toggle button in the top-right corner to switch between light and dark modes. Your preference will be saved automatically!
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
