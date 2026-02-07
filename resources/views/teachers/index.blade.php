@extends('layouts.app')

@section('title', 'Teachers - ERP System')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
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

@section('header_title', 'Teachers Directory')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-1 bg-slate-900 rounded-full"></div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">All Teachers</h1>
                <p class="text-sm text-slate-500 font-medium">Manage faculty and staff records</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            @can('teacher-create')
            <a href="{{ route('teachers.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all duration-300 shadow-lg shadow-slate-200 group">
                <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Teacher
            </a>
            @endcan
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Teachers</p>
                <p class="text-2xl font-black text-slate-900">{{ $teachers->count() }}</p>
            </div>
        </div>
        <!-- Add more stats as needed -->
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Search & Filter Area -->
        <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" id="custom-search" placeholder="Search by name, email or phone..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-slate-200 transition-all font-medium text-slate-600">
            </div>
            <div class="flex items-center gap-3">
                <select id="custom-length" class="bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-slate-200 transition-all font-bold text-slate-600 px-4">
                    <option value="10">10 Rows</option>
                    <option value="25">25 Rows</option>
                    <option value="50">50 Rows</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="teachers-table">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Teacher</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Contact</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Designation</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Joined Date</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($teachers as $teacher)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($teacher->photo)
                                    <img src="{{ Storage::url($teacher->photo) }}" class="h-10 w-10 rounded-xl object-cover ring-2 ring-slate-100 group-hover:ring-slate-200 transition-all">
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-bold group-hover:bg-slate-200 transition-all">
                                        {{ substr($teacher->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $teacher->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium tracking-tight">ID: #{{ str_pad($teacher->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700">{{ $teacher->phone }}</p>
                            <p class="text-xs text-slate-400 font-medium">{{ $teacher->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[11px] font-black uppercase tracking-wider">
                                {{ $teacher->designation }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700">{{ $teacher->join_date->format('M d, Y') }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $teacher->join_date->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($teacher->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('teacher-view')
                                <a href="{{ route('teachers.show', $teacher) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all shadow-sm shadow-transparent hover:shadow-indigo-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @endcan
                                @can('teacher-edit')
                                <a href="{{ route('teachers.edit', $teacher) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all shadow-sm shadow-transparent hover:shadow-amber-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @endcan
                                @can('teacher-delete')
                                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all shadow-sm shadow-transparent hover:shadow-red-100">
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

        <!-- Footer -->
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row items-center justify-between gap-6">
            <div id="custom-info" class="text-xs font-black text-slate-400 uppercase tracking-widest">
                <!-- Info text injected via JS -->
            </div>
            <div id="custom-pagination" class="flex items-center">
                <!-- Pagination buttons injected via JS -->
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
        var table = $('#teachers-table').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "responsive": true,
            "pageLength": 10,
            "dom": "rt",
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
                    `Showing <span class="text-slate-900 font-bold">${start}</span> to <span class="text-slate-900 font-bold">${info.end}</span> of <span class="text-slate-900 font-bold">${info.recordsTotal}</span> Teachers`
                );

                // Update Pagination Buttons
                var $pagination = $('#custom-pagination');
                $pagination.empty();
                
                if (info.pages > 1) {
                    var $prev = $('<button type="button" class="custom-page-btn">Prev</button>');
                    if (info.page === 0) $prev.addClass('disabled');
                    else $prev.on('click', function() { api.page('previous').draw('page'); });
                    $pagination.append($prev);

                    for (var i = 0; i < info.pages; i++) {
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

                    var $next = $('<button type="button" class="custom-page-btn">Next</button>');
                    if (info.page === info.pages - 1) $next.addClass('disabled');
                    else $next.on('click', function() { api.page('next').draw('page'); });
                    $pagination.append($next);
                }
            }
        });

        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#custom-length').on('change', function() {
            table.page.len($(this).val()).draw();
        });
    });
</script>
@endpush
