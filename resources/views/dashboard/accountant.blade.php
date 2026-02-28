@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Accountant Dashboard</h1>
            <p class="text-slate-600">Welcome back, {{ auth()->user()->name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-slate-50 rounded-xl shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Today's Collection</p>
                <p class="text-2xl font-bold text-slate-800 mt-2">৳{{ number_format($stats['today_collection']) }}</p>
            </div>
            
            <div class="bg-slate-50 rounded-xl shadow p-6">
                <p class="text-sm text-slate-600 font-medium">This Month</p>
                <p class="text-2xl font-bold text-slate-800 mt-2">৳{{ number_format($stats['month_collection']) }}</p>
            </div>

            <div class="bg-slate-50 rounded-xl shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Month Expenses</p>
                <p class="text-2xl font-bold text-slate-800 mt-2">৳{{ number_format($stats['month_expenses']) }}</p>
            </div>

            <div class="bg-slate-50 rounded-xl shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Pending Expenses</p>
                <p class="text-2xl font-bold text-slate-800 mt-2">{{ $stats['pending_expenses'] }}</p>
            </div>
        </div>

        <!-- Recent Payments Table -->
        <div class="bg-slate-50 rounded-xl shadow mb-6">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Recent Payments</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Receipt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-50 divide-y divide-gray-200">
                        @forelse($recentPayments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800">{{ $payment->receipt_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800">{{ $payment->student->full_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">৳{{ number_format($payment->amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-slate-500">No recent payments</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
