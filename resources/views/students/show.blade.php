@extends('layouts.app')

@section('title', 'Student Details - ERP System')

@section('content')
<div class="py-6 space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate tracking-tight">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Sidebar: Student Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 text-center bg-slate-50 border-b border-slate-100">
                    <div class="relative inline-block">
                         @if($student->photo)
                            <img class="h-32 w-32 rounded-3xl object-cover border-4 border-white shadow-xl" src="{{ Storage::url($student->photo) }}" alt="{{ $student->full_name }}">
                        @else
                            <div class="h-32 w-32 rounded-3xl bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-4xl border-4 border-white shadow-xl mx-auto">
                                {{ substr($student->first_name, 0, 1) }}
                            </div>
                        @endif
                        <span class="absolute -bottom-2 -right-2 block h-6 w-6 rounded-full ring-4 ring-white {{ $student->status === 'active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                    </div>
                    <h3 class="mt-6 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $student->full_name }}</h3>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $student->student_id }}</p>
                    
                    <div class="mt-6 flex justify-center gap-2">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ $student->class->name ?? 'No Class' }}
                        </span>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                            Sec: {{ $student->section->name ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-slate-400 uppercase text-[10px] tracking-widest">Enrollment Date</span>
                        <span class="font-bold text-slate-700">{{ $student->enrollment_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-slate-400 uppercase text-[10px] tracking-widest">Guardian</span>
                        <span class="font-bold text-slate-700 text-right">{{ $student->guardian_name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold text-slate-400 uppercase text-[10px] tracking-widest">Phone</span>
                        <span class="font-bold text-slate-700">{{ $student->phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact/Address Mini Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Contact Details</h4>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-slate-600 truncate">{{ $student->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-sm font-bold text-slate-600">{{ $student->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content: Fee Status Ledger (Main Task) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Fee Status Ledger -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Fee Status Ledger</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Academic Year: {{ $academicYear }}</p>
                    </div>
                    @can('payment-create')
                    <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold uppercase rounded-lg hover:bg-indigo-700 transition-all">
                        Collect Fee
                    </a>
                    @endcan
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-slate-50">
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
                            <tr>
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
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">
                    <div class="flex items-center gap-1.5"><div class="h-3 w-3 rounded bg-emerald-500"></div> Paid</div>
                    <div class="flex items-center gap-1.5"><div class="h-3 w-3 rounded bg-amber-500"></div> Partial</div>
                    <div class="flex items-center gap-1.5"><div class="h-3 w-3 rounded bg-rose-500"></div> Due</div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800">Recent Payment Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Receipt #</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fee Type</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($student->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">{{ $payment->receipt_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $payment->payment_date->format('d M, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-slate-700">{{ $payment->fee_type }}</span>
                                    @if($payment->billing_month)
                                    <span class="ml-1 text-[10px] font-black bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded uppercase">
                                        {{ date('M', mktime(0, 0, 0, $payment->billing_month, 10)) }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-slate-900">{{ number_format($payment->amount, 2) }} TK</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">No payments found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
