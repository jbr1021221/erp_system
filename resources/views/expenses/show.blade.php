@extends('layouts.app')

@section('title', 'Expense Details - ERP System')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
  <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Expenses</a>
@endsection


@section('content')

    
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-semibold text-slate-800">
                    Expense Details
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-slate-500">
                        <span class="font-medium text-slate-800 mr-1">Ref:</span> {{ $expense->reference_no }}
                    </div>
                     <div class="mt-2 flex items-center text-sm text-slate-500">
                        <span class="font-medium text-slate-800 mr-1">Date:</span> {{ $expense->expense_date->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('expenses.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
                
                @can('expense-edit')
                    @if($expense->status !== 'approved')
                    <a href="{{ route('expenses.edit', $expense) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Edit Expense
                    </a>
                    @endif
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium leading-6 text-slate-800">Expense Information</h3>
                         @php
                            $statusClasses = match($expense->status) {
                                'approved' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-slate-200 text-slate-800'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusClasses }}">
                            Status: {{ ucfirst($expense->status) }}
                        </span>
                    </div>
                    <div class="px-6 py-5">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Description</dt>
                                <dd class="mt-1 text-sm text-slate-800">{{ $expense->title }}</dd>
                                @if($expense->description)
                                <dd class="mt-2 text-sm text-slate-600 border-l-2 border-slate-200 pl-3 italic">
                                    "{{ $expense->description }}"
                                </dd>
                                @endif
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-slate-500">Category</dt>
                                <dd class="mt-1 text-sm text-slate-800 font-semibold">{{ $expense->category->name }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-slate-500">Amount</dt>
                                <dd class="mt-1 text-lg font-bold text-slate-800">৳{{ number_format($expense->amount, 2) }}</dd>
                            </div>

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-slate-500">Payment Method</dt>
                                <dd class="mt-1 text-sm text-slate-800 capitalize">{{ str_replace('_', ' ', $expense->payment_method) }}</dd>
                            </div>
                            
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-slate-500">Vendor</dt>
                                <dd class="mt-1 text-sm text-slate-800">{{ $expense->vendor->name ?? 'N/A' }}</dd>
                            </div>

                             @if($expense->status === 'approved')
                            <div class="sm:col-span-2 border-t border-slate-200 pt-4 mt-2">
                                <div class="flex items-center text-sm text-slate-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Approved by Admin on {{ $expense->updated_at->format('M d, Y') }}
                                </div>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Actions & Attachment -->
            <div class="space-y-6">
                <!-- Approval Action -->
                @can('expense-approve')
                    @if($expense->status === 'pending')
                    <div class="bg-white rounded-lg border border-slate-200 p-6">
                        <h3 class="text-lg font-medium text-slate-800 mb-4">Approval Request</h3>
                        <p class="text-sm text-slate-500 mb-6">Review this expense and take action.</p>
                        
                        <div class="flex space-x-3">
                            <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Approve
                                </button>
                            </form>
                            <!-- Reject button could be added here similar to approve -->
                        </div>
                    </div>
                    @endif
                @endcan

                <!-- Attachment -->
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-medium leading-6 text-slate-800">Attachment</h3>
                    </div>
                    <div class="p-6">
                        @if($expense->attachment)
                            <div class="flex items-center justify-between p-6 border border-slate-200 rounded-xl">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="ml-2 text-sm font-medium text-slate-800">Document</span>
                                </div>
                                <a href="{{ Storage::url($expense->attachment) }}" target="_blank" class="text-slate-800 hover:text-slate-800 font-medium text-sm">Download</a>
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic text-center">No attachment provided.</p>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-slate-100 rounded-xl p-6 border border-slate-200">
                    <div class="flex items-center text-sm text-slate-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Created {{ $expense->created_at->diffForHumans() }} by {{ $expense->createdBy->name ?? 'Unknown' }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
