@extends('layouts.app')

@section('title', 'Students - ERP System')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    /* Reset DataTables default styles to play nice with Tailwind */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter, 
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_processing, 
    .dataTables_wrapper .dataTables_paginate {
        display: none !important;
    }
    
    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    /* Custom Pagination Styling */
    .custom-page-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 32px !important;
        min-width: 32px !important;
        padding: 0 10px !important;
        margin: 0 4px !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }

    .custom-page-btn:hover {
        background-color: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
    }

    .custom-page-btn.active {
        background-color: #0f172a !important;
        border-color: #0f172a !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }

    .custom-page-btn.disabled {
        opacity: 0.3 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
</style>
@endpush

@section('header_title', 'Students Directory')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-2xl sm:truncate tracking-tight">
                Students List
            </h2>
            <p class="mt-1 text-xs font-bold text-slate-400 uppercase tracking-widest">
                Manage your student database and fee collections
            </p>
        </div>
        @can('student-create')
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Admission
            </a>
        </div>
        @endcan
    </div>

    <!-- Toolbar: Search & Stats -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="relative w-full md:w-96">
            <input type="text" id="custom-search" placeholder="Search by name, ID or email..." 
                class="block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 pl-11 text-sm font-medium text-slate-700 placeholder-slate-400 focus:bg-white focus:border-slate-900 focus:ring-0 transition-all">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Display</span>
                <select id="custom-length" class="rounded-lg border-slate-200 text-xs font-bold text-slate-600 focus:border-slate-900 focus:ring-0 py-1 pl-2 pr-8">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="h-8 w-px bg-slate-100 mx-2"></div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Total:</span>
                <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 text-sm font-black">
                    {{ $students->count() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto min-h-[400px]">
            <table id="students-table" class="min-w-full divide-y divide-slate-100 w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">SL</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Student Name</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Student ID</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Classification</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($students as $index => $student)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 relative">
                                    @if($student->photo)
                                        <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-white shadow-sm" src="{{ Storage::url($student->photo) }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-sm ring-2 ring-white shadow-sm transition-colors group-hover:bg-indigo-50 group-hover:text-indigo-600">
                                            {{ substr($student->first_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('students.show', $student) }}" class="text-sm font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                                        {{ $student->full_name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-500 font-mono tracking-wider">
                            {{ $student->student_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200 transition-colors group-hover:bg-amber-50 group-hover:text-amber-600 group-hover:border-amber-100">
                                {{ $student->class->name ?? 'N/A' }} • {{ $student->section->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-2">
                                @can('payment-create')
                                <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl hover:bg-emerald-500 hover:text-white transition-all text-xs font-bold" title="Collect Fee">
                                    Fee
                                </a>
                                @endcan

                                <a href="{{ route('payments.index', ['search' => $student->student_id]) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-100 rounded-xl hover:bg-amber-500 hover:text-white transition-all text-xs font-bold" title="View Receipts">
                                    History
                                </a>

                                @can('student-delete')
                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center p-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-600 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Premium Custom Footer -->
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row items-center justify-between gap-6">
            <div id="custom-info" class="text-xs font-black text-slate-400 uppercase tracking-widest">
                <!-- Info text will be injected here -->
            </div>
            <div id="custom-pagination" class="flex items-center">
                <!-- Pagination buttons will be injected here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#students-table').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "responsive": true,
            "pageLength": 10,
            "dom": "rt", // Only show the processing and table
            "language": {
                "paginate": {
                    "previous": "Prev",
                    "next": "Next"
                }
            },
            "drawCallback": function(settings) {
                var api = this.api();
                var info = api.page.info();
                
                // Update Info Text
                var start = info.recordsTotal > 0 ? info.start + 1 : 0;
                $('#custom-info').html(
                    `Showing <span class="text-slate-900 font-bold">${start}</span> to <span class="text-slate-900 font-bold">${info.end}</span> of <span class="text-slate-900 font-bold">${info.recordsTotal}</span> Students`
                );

                // Update Pagination Buttons
                var $pagination = $('#custom-pagination');
                $pagination.empty();
                
                if (info.pages > 1) {
                    // Previous
                    var $prev = $('<button type="button" class="custom-page-btn">Prev</button>');
                    if (info.page === 0) $prev.addClass('disabled');
                    else $prev.on('click', function() { api.page('previous').draw('page'); });
                    $pagination.append($prev);

                    // Pages
                    for (var i = 0; i < info.pages; i++) {
                        // Logic to show limited page numbers with ellipsis
                        if (info.pages > 5 && i > 0 && i < info.pages - 1 && Math.abs(i - info.page) > 1) {
                            if (i == 1 || i == info.pages - 2) $pagination.append('<span class="px-1 text-slate-300">...</span>');
                            continue;
                        }
                        
                        var $page = $('<button type="button" class="custom-page-btn">' + (i + 1) + '</button>');
                        if (i === info.page) $page.addClass('active');
                        
                        (function(idx) {
                            $page.on('click', function() { api.page(idx).draw('page'); });
                        })(i);
                        
                        $pagination.append($page);
                    }

                    // Next
                    var $next = $('<button type="button" class="custom-page-btn">Next</button>');
                    if (info.page === info.pages - 1) $next.addClass('disabled');
                    else $next.on('click', function() { api.page('next').draw('page'); });
                    $pagination.append($next);
                }
            }
        });

        // Custom Search Logic
        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Custom Length Logic
        $('#custom-length').on('change', function() {
            table.page.len($(this).val()).draw();
        });
    });
</script>
@endpush


