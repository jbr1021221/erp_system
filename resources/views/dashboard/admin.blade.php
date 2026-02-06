@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
                <p class="text-gray-500 mt-2 text-lg">Welcome back, <span class="font-semibold text-indigo-600">{{ auth()->user()->name }}</span></p>
            </div>
            <div class="hidden sm:block">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_students'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 flex items-center font-medium">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        {{ $stats['active_students'] }} Active
                    </span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-gray-500">{{ $stats['inactive_students'] }} Inactive</span>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">৳{{ number_format($stats['total_revenue']) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium bg-green-50 px-2.5 py-0.5 rounded-full">
                        +৳{{ number_format($stats['this_month_revenue']) }}
                    </span>
                    <span class="ml-2 text-gray-400">this month</span>
                </div>
            </div>

            <!-- Today's Collection -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Today's Collection</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">৳{{ number_format($stats['today_revenue']) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    Collected today from payments
                </div>
            </div>

            <!-- Outstanding Fees -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Outstanding Fees</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">৳{{ number_format($stats['total_outstanding']) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if($stats['pending_expenses'] > 0)
                    <span class="text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-full font-medium">
                        {{ $stats['pending_expenses'] }} pending expenses
                    </span>
                    @else
                    <span class="text-green-500 font-medium">No pending expenses</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Revenue Trend</h3>
                    <select class="text-sm border-gray-200 rounded-lg text-gray-500 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Last 6 Months</option>
                    </select>
                </div>
                <div class="relative h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Class-wise Students -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Students by Class</h3>
                <div class="space-y-5 overflow-y-auto max-h-72 pr-2">
                    @foreach($classWiseStudents as $class)
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold mr-3 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                    {{ substr($class->name, -2) }}
                                </span>
                                <span class="text-sm font-semibold text-gray-700">{{ $class->name }}</span>
                            </div>
                            <span class="text-sm text-gray-500 font-medium">{{ $class->students_count }} Students</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-500 ease-out" style="width: {{ ($stats['total_students'] > 0) ? ($class->students_count / $stats['total_students']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activities Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Admissions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Recent Admissions</h3>
                    @can('student-list')
                    <a href="{{ route('students.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">View All</a>
                    @endcan
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentAdmissions as $student)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($student->photo)
                                        <img src="{{ Storage::url($student->photo) }}" alt="{{ $student->full_name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200">
                                            {{ substr($student->first_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $student->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->class->name ?? 'N/A' }} {{ $student->section->name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-md font-medium">New</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        No recent admissions found.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Recent Payments</h3>
                    @can('payment-list')
                    <a href="{{ route('payments.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">View All</a>
                    @endcan
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentPayments as $payment)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $payment->student->full_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $payment->receipt_number }} • {{ ucfirst($payment->payment_method) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">৳{{ number_format($payment->amount) }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $payment->payment_date->format('M d') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        No recent payments found.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueData = @json($monthlyRevenue);

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueData.map(item => item.month),
        datasets: [{
            label: 'Revenue',
            data: revenueData.map(item => item.total),
            borderColor: '#4f46e5', // Indigo 600
            backgroundColor: 'rgba(79, 70, 229, 0.05)',
            borderWidth: 3,
            fill: true,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#4f46e5',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 13
                },
                bodyFont: {
                    size: 13
                },
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ৳' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    borderDash: [2, 4],
                    color: '#f3f4f6',
                    drawBorder: false
                },
                ticks: {
                    font: {
                        size: 11
                    },
                    color: '#9ca3af',
                    callback: function(value) {
                        return '৳' + (value/1000) + 'k';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 11
                    },
                    color: '#9ca3af'
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index',
        },
    }
});
</script>
@endpush