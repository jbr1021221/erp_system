@extends('layouts.app')

@section('title', 'Payments - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate">
                    Payments History
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Track all fee collections and financial transactions.
                </p>
            </div>
            @can('payment-create')
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('payments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Record Payment
                </a>
            </div>
            @endcan
        </div>

        <!-- Filters -->
        <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 p-5 mb-8">
            <form action="{{ route('payments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" class="focus:ring-slate-500 focus:border-slate-500 block w-full pl-10 sm:text-sm border-slate-300 rounded-xl py-2.5" placeholder="Search by receipt, student name or ID..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <input type="date" name="start_date" class="block w-full pl-3 pr-2 py-2.5 text-lg border-slate-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-xl" value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <div>
                        <input type="date" name="end_date" class="block w-full pl-3 pr-2 py-2.5 text-lg border-slate-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-xl" value="{{ request('end_date') }}" placeholder="End Date">
                    </div>
                </div>

                <div>
                    <label for="payment_method" class="sr-only">Method</label>
                    <select name="payment_method" id="payment_method" class="block w-full pl-3 pr-10 py-2.5 text-lg border-slate-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-xl">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end">
                    <a href="{{ route('payments.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-xl text-slate-700 bg-slate-50 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Payments Table -->
        <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Payment Info
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Student
                            </th>
                             <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Method
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="relative px-6 py-3 text-right">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-50 divide-y divide-gray-200">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">{{ $payment->receipt_number }}</span>
                                    <span class="text-xs text-slate-500">{{ $payment->fee_type ?? 'Fee' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                         @if($payment->student->photo ?? false)
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url($payment->student->photo) }}" alt="">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs">
                                                {{ substr($payment->student->first_name ?? 'U', 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $payment->student->full_name ?? 'Unknown Student' }}</div>
                                        <div class="text-xs text-slate-500">{{ $payment->student->student_id ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $payment->payment_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800 capitalize">
                                    {{ str_replace('_', ' ', $payment->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-900">
                                ৳{{ number_format($payment->amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-slate-800 hover:text-slate-900">Receipt</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-lg font-medium text-slate-900">No payments found</p>
                                    <p class="text-sm text-slate-500">Try adjusting your filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $payments->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
