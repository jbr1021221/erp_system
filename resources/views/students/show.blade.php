@extends('layouts.app')

@section('title', 'Student Details - ERP System')

@section('content')
<div class="py-6 space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-semibold text-slate-800 tracking-tight">
                {{ $student->full_name }}'s Academic Profile
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                Back to List
            </a>
            @can('student-edit')
            <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 transition-all">
                Edit Profile
            </a>
            @endcan
        </div>
    </div>

    <div class="space-y-6">
        <!-- Unified Student Profile & Fee Ledger Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Profile Header -->
            <div class="p-6 bg-slate-50 border-b border-slate-100">
                <div class="flex flex-col lg:flex-row items-center gap-6">
                    <!-- Photo & Basic Info -->
                    <div class="flex items-center gap-5 flex-shrink-0">
                        <div class="relative">
                            @if($student->photo)
                                <img class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white shadow-sm" src="{{ Storage::url($student->photo) }}" alt="{{ $student->full_name }}">
                            @else
                                <div class="h-20 w-20 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-2xl ring-4 ring-white shadow-sm">
                                    {{ substr($student->first_name, 0, 1) }}
                                </div>
                            @endif
                            <span class="absolute -bottom-1 -right-1 block h-4 w-4 rounded-full ring-2 ring-white {{ $student->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">{{ $student->full_name }}</h2>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $student->student_id }}</p>
                            <div class="mt-2 flex gap-2">
                                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-indigo-600 text-white">
                                    {{ $student->class->name ?? 'No Class' }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-white text-slate-600 border border-slate-200 shadow-sm">
                                    Sec: {{ $student->section->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden lg:block h-16 w-px bg-slate-200"></div>

                    <!-- Detailed Info Grid -->
                    <div class="flex-grow w-full lg:w-auto grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="space-y-1">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Guardian</span>
                            <span class="block text-sm font-bold text-slate-700 truncate">{{ $student->guardian_name }}</span>
                            <span class="block text-xs font-medium text-slate-500">{{ $student->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Enrollment</span>
                            <span class="block text-sm font-bold text-slate-700">{{ $student->enrollment_date->format('d M, Y') }}</span>
                            <span class="block text-xs font-medium text-slate-500">Active Student</span>
                        </div>
                        <div class="col-span-2 md:col-span-2 space-y-1">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Address & Contact</span>
                            <span class="block text-sm font-bold text-slate-700 truncate">{{ $student->email ?? 'No Email' }}</span>
                            <span class="block text-xs font-medium text-slate-500 truncate" title="{{ $student->address }}">{{ $student->address ?? 'No Address' }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex-shrink-0 ml-auto">
                         @can('payment-create')
                        <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-xs font-bold uppercase rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Collect Fee
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Fee Ledger Section Title -->
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
               <div class="flex items-center gap-3">
                   <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Fee Status Ledger</h3>
                   <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500">{{ $academicYear }}</span>
               </div>
               <div class="flex gap-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    <div class="flex items-center gap-1.5"><div class="h-2 w-2 rounded-full bg-emerald-500"></div> Paid</div>
                    <div class="flex items-center gap-1.5"><div class="h-2 w-2 rounded-full bg-amber-500"></div> Partial</div>
                    <div class="flex items-center gap-1.5"><div class="h-2 w-2 rounded-full bg-rose-500"></div> Due</div>
               </div>
            </div>

            <!-- Ledger Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fee Type</th>
                            @for($m = 1; $m <= 12; $m++)
                            <th class="px-2 py-3 text-center text-[10px] font-black text-slate-400 uppercase">
                                {{ substr(date('F', mktime(0, 0, 0, $m, 10)), 0, 3) }}
                            </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($ledger as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-bold text-slate-700">{{ $item['fee']->fee_type }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">{{ $item['fee']->frequency }} • {{ number_format($item['fee']->amount) }}TK</p>
                            </td>
                            @for($m = 1; $m <= 12; $m++)
                            <td class="px-1 py-4 text-center">
                                @if(isset($item['status'][$m]))
                                    <div class="inline-flex h-6 w-8 items-center justify-center rounded-md border
                                        @if($item['status'][$m] == 'paid') bg-emerald-50 border-emerald-100 text-emerald-600 
                                        @elseif($item['status'][$m] == 'partial') bg-amber-50 border-amber-100 text-amber-600 
                                        @else bg-rose-50 border-rose-100 text-rose-600 @endif"
                                        title="{{ ucfirst($item['status'][$m]) }}">
                                        @if($item['status'][$m] == 'paid')
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        @else
                                            <span class="text-[8px] font-black">X</span>
                                        @endif
                                    </div>
                                @elseif(isset($item['status']['one_time']))
                                    @if($loop->first)
                                    <div class="inline-flex h-6 w-full items-center justify-center rounded-md border text-[9px] font-bold uppercase
                                        @if($item['status']['one_time'] == 'paid') bg-emerald-50 border-emerald-100 text-emerald-600 
                                        @elseif($item['status']['one_time'] == 'partial') bg-amber-50 border-amber-100 text-amber-600 
                                        @else bg-rose-50 border-rose-100 text-rose-600 @endif">
                                        {{ $item['status']['one_time'] }}
                                    </div>
                                    @endif
                                @else
                                    <span class="text-[10px] text-slate-200">—</span>
                                @endif
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment History Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Payment History</h3>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                    Total Paid: {{ number_format($student->payments->sum('amount'), 2) }} TK
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="paymentHistoryTable" class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Receipt #</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fee Type</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Method</th>
                            <th class="px-6 py-3 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($student->payments as $payment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                                {{ $payment->receipt_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $payment->payment_date->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-slate-700">{{ $payment->fee_type }}</span>
                                @if($payment->billing_month)
                                <span class="ml-1 text-[10px] font-black bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded uppercase">
                                    {{ date('M', mktime(0, 0, 0, $payment->billing_month, 10)) }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 capitalize">
                                {{ str_replace('_', ' ', $payment->payment_method ?? 'N/A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                    {{ $payment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-slate-800">
                                {{ number_format($payment->amount, 2) }} TK
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('payments.receipt', $payment->receipt_number) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wide">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Receipt
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">No payment history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <style>
        /* Overrides for DataTables to match app theme */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: #ffffff !important;
            color: #1e293b !important; /* slate-800 */
            border-color: #e2e8f0 !important; /* slate-200 */
            border-width: 1px !important;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            padding-left: 0.75rem;
            border-radius: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            ring-color: #6366f1; /* indigo-500 */
            border-color: #6366f1 !important;
            outline: none;
            box-shadow: 0 0 0 1px #6366f1;
        }
        
        /* Table row styling */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0 !important; /* slate-200 */
        }
        
        table.dataTable tbody tr {
            background-color: #ffffff !important;
            color: #334155 !important; /* slate-700 */
        }
        
        table.dataTable tbody tr.odd {
             background-color: #ffffff !important;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f8fafc !important; /* slate-50 */
        }

        /* Pagination buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #64748b !important; /* slate-500 */
            border-radius: 0.375rem !important;
            margin-left: 0.25rem;
            padding: 0.25rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important; /* slate-100 */
            color: #1e293b !important; /* slate-800 */
            border-color: #cbd5e1 !important; /* slate-300 */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #4f46e5 !important; /* indigo-600 */
            color: #ffffff !important;
            border-color: #4f46e5 !important;
        }

        .dataTables_wrapper .dataTables_info {
            color: #64748b !important; /* slate-500 */
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#paymentHistoryTable').DataTable({
                responsive: true,
                order: [[ 1, "desc" ]],
                columnDefs: [
                    { orderable: false, targets: 6 }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search payments..."
                }
            });
        });
    </script>
@endpush
