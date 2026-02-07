@extends('layouts.app')

@section('title', 'Fee Reports - ERP System')

@section('header_title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate tracking-tight">
                Yearly Fee Collection Summary
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <form action="{{ route('reports.fees') }}" method="GET" class="flex items-center gap-2">
                <select name="academic_year" onchange="this.form.submit()" class="rounded-xl border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ $academicYear == $year ? 'selected' : '' }}>Year: {{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Collected ({{ $academicYear }})</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($yearlyTotal, 2) }} TK</p>
            <div class="mt-2 flex items-center text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md w-fit">
                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                Current Academic Year
            </div>
        </div>
    </div>

    <!-- Details Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Income Breakdown by Fee Type</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Fee Type</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Payment Count</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Amount Collected</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Contribution</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($stats as $row)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                            {{ $row->fee_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-600 font-medium">
                            {{ number_format($row->payment_count) }} Transactions
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-900 font-bold">
                            {{ number_format($row->total_collected, 2) }} TK
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                             <div class="flex items-center justify-end gap-2">
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $yearlyTotal > 0 ? round(($row->total_collected / $yearlyTotal) * 100, 1) : 0 }}%
                                </span>
                                <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-indigo-500 h-full" style="width: {{ $yearlyTotal > 0 ? ($row->total_collected / $yearlyTotal) * 100 : 0 }}%"></div>
                                </div>
                             </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                            No payment data found for the selected academic year.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($stats->isNotEmpty())
                <tfoot class="bg-slate-50">
                    <tr>
                        <td class="px-6 py-4 text-sm font-extrabold text-slate-900">Grand Total</td>
                        <td class="px-6 py-4 text-right text-sm font-extrabold text-slate-900">{{ number_format($stats->sum('payment_count')) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-extrabold text-slate-900">{{ number_format($yearlyTotal, 2) }} TK</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
