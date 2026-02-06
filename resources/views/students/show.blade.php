@extends('layouts.app')

@section('title', 'Student Details - ERP System')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Student Profile
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to List
                </a>
                
                @can('student-edit')
                <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Student
                </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Basic Info & Photo -->
            <div class="md:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-6 text-center">
                        <div class="relative inline-block">
                             @if($student->photo)
                                <img class="h-32 w-32 rounded-full object-cover border-4 border-indigo-50" src="{{ Storage::url($student->photo) }}" alt="{{ $student->full_name }}">
                            @else
                                <div class="h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-4xl border-4 border-indigo-50 mx-auto">
                                    {{ substr($student->first_name, 0, 1) }}
                                </div>
                            @endif
                            <span class="absolute bottom-2 right-2 block h-4 w-4 rounded-full ring-2 ring-white {{ $student->status === 'active' ? 'bg-green-400' : 'bg-gray-300' }}"></span>
                        </div>
                        
                        <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $student->full_name }}</h3>
                        <p class="text-sm text-gray-500">ID: {{ $student->student_id }}</p>
                        
                        <div class="mt-4 flex justify-center space-x-2">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $student->class->name ?? 'No Class' }}
                            </span>
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Section {{ $student->section->name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 p-6 bg-gray-50">
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-gray-500">Status</span>
                            <span class="font-semibold text-gray-900 capitalize">{{ $student->status }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="text-gray-500">Joined</span>
                            <span class="font-semibold text-gray-900">{{ $student->enrollment_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Gender</span>
                            <span class="font-semibold text-gray-900 capitalize">{{ $student->gender }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Contact Details</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-600 break-all">{{ $student->email ?? 'No email provided' }}</span>
                        </div>
                         <div class="flex items-start">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ $student->phone ?? 'No phone provided' }}</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ $student->address ?? 'No address provided' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Tabs -->
            <div class="md:col-span-2 space-y-6">
                <!-- Guardian Info -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Guardian Information</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Guardian Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $student->guardian_name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->guardian_relation }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->guardian_phone }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->guardian_email ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Payment History (Tab) -->
                 <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Payment History</h3>
                         @can('payment-create')
                         <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                            + New Payment
                        </a>
                        @endcan
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Details</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($student->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-medium">
                                        {{ $payment->receipt_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->payment_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="font-medium text-gray-900">{{ $payment->fee_type ?? 'Fee' }}</div>
                                        <div class="text-xs">{{ ucfirst($payment->payment_method) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">
                                        ৳{{ number_format($payment->amount) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No payment history found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
