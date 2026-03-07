@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
@endsection

@section('header_title', 'Payment Receipt')

@section('content')

{{-- Action Buttons (no-print) --}}
<div class="no-print flex justify-between items-center mb-4 max-w-4xl mx-auto">
    <a href="{{ route('payments.index') }}"
       class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Payments
    </a>
    <div class="flex gap-3">
        <button onclick="window.print()"
                class="inline-flex items-center px-6 py-2 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Receipt
        </button>
    </div>
</div>

@php
    $student = $payment->student;

    // ── Build fee rows from the $payments collection (all rows sharing this receipt_number)
    $feeRows     = [];
    $totalAmount = 0;

    foreach ($payments as $item) {
        // Period label
        if ($item->billing_month) {
            $monthName = DateTime::createFromFormat('!m', $item->billing_month)->format('F');
            $period    = $monthName . ' ' . $item->billing_year;
        } else {
            $period = (string) $item->billing_year;
        }

        // Discount: compare fee_structure amount vs actual paid amount
        $structureAmount = $item->feeStructure?->amount ?? $item->amount;
        $discount        = max(0, $structureAmount - $item->amount);
        $net             = $item->amount;

        $feeRows[] = [
            'name'            => $item->fee_type . ($item->billing_month ? ' (' . $period . ')' : ''),
            'original_amount' => $structureAmount,
            'discount'        => $discount,
            'amount'          => $net,
        ];

        $totalAmount += $net;
    }

    $totalOriginal = collect($feeRows)->sum('original_amount');
    $totalDiscount = collect($feeRows)->sum('discount');
    $showDiscount  = $totalDiscount > 0;

    // ── Amount in words
    function numberToWords(int $number): string {
        $ones  = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
                  'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
                  'Seventeen', 'Eighteen', 'Nineteen'];
        $tens  = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        if ($number === 0) return 'Zero';
        $words = '';
        if ($number >= 100000) {
            $words .= numberToWords((int)($number / 100000)) . ' Lakh ';
            $number  %= 100000;
        }
        if ($number >= 1000) {
            $words .= numberToWords((int)($number / 1000)) . ' Thousand ';
            $number  %= 1000;
        }
        if ($number >= 100) {
            $words .= $ones[(int)($number / 100)] . ' Hundred ';
            $number  %= 100;
        }
        if ($number >= 20) {
            $words .= $tens[(int)($number / 10)] . ' ';
            $number  %= 10;
        }
        if ($number > 0) {
            $words .= $ones[$number] . ' ';
        }
        return trim($words);
    }

    $amountInWords = numberToWords((int) $totalAmount);
@endphp

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- RECEIPT CONTENT (printed)                                          --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="receipt-content" style="
    max-width: 900px;
    margin: 0 auto;
    background: white;
    padding: 12px;
    border: 1px solid #059669;
    font-family: Arial, sans-serif;
    font-size: 12px;
    line-height: 1.2;
    color: #000;
">

    {{-- ── HEADER ── --}}
    <div style="position:relative; min-height:130px; max-width:95%; margin:0 0 0 15px;">

        {{-- Logo left --}}
        <div style="position:absolute; top:0; left:0; width:100px; height:100px;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+CiAgPHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNmMGZkZjQiIHJ4PSI4Ii8+CiAgPGNpcmNsZSBjeD0iNTAiIGN5PSIzNSIgcj0iMTgiIGZpbGw9IiMwNTk2NjkiLz4KICA8cmVjdCB4PSIzNSIgeT0iNTAiIHdpZHRoPSIzMCIgaGVpZ2h0PSIzNSIgZmlsbD0iIzA1OTY2OSIvPgogIDxwb2x5Z29uIHBvaW50cz0iNTAsMTUgNjUsNTAgMzUsNTAiIGZpbGw9IiMwNDc4NTciLz4KICA8cmVjdCB4PSI0NCIgeT0iNjIiIHdpZHRoPSIxMiIgaGVpZ2h0PSIyMyIgZmlsbD0iI2YwZmRmNCIgcng9IjYiLz4KICA8dGV4dCB4PSI1MCIgeT0iOTUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtc2l6ZT0iNyIgZmlsbD0iIzA1OTY2OSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCI+QUwtQUtISVJBSDwvdGV4dD4KPC9zdmc+" alt="Logo" style="max-width:100%; max-height:100%; object-fit:contain;">
        </div>

        {{-- Banner center --}}
        <div style="text-align:center; padding:0 110px; padding-top:10px;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MDAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA2MDAgODAiPgogIDxyZWN0IHdpZHRoPSI2MDAiIGhlaWdodD0iODAiIGZpbGw9IiNmMGZkZjQiIHJ4PSI2Ii8+CiAgPGxpbmUgeDE9IjIwIiB5MT0iNDAiIHgyPSI1ODAiIHkyPSI0MCIgc3Ryb2tlPSIjMDU5NjY5IiBzdHJva2Utd2lkdGg9IjAuNSIgb3BhY2l0eT0iMC4zIi8+CiAgPHRleHQgeD0iMzAwIiB5PSIzMiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1zaXplPSIyMiIgZmlsbD0iIzA1OTY2OSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCIgbGV0dGVyLXNwYWNpbmc9IjMiPkFMLUFLSElSQUg8L3RleHQ+CiAgPHRleHQgeD0iMzAwIiB5PSI1NSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzA0Nzg1NyIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCIgbGV0dGVyLXNwYWNpbmc9IjYiPklOVEVSTkFUSU9OQUwgQUNBREVNWTwvdGV4dD4KICA8cmVjdCB4PSIxMjAiIHk9IjYyIiB3aWR0aD0iMzYwIiBoZWlnaHQ9IjIiIGZpbGw9IiMwNTk2NjkiIHJ4PSIxIi8+Cjwvc3ZnPg==" alt="Al Akhirah International Academy" style="max-width:100%; height:auto; max-height:90px; display:block; margin:0 auto;">
            <div style="margin-top:8px;">
                <div style="font-family:'Century Gothic',sans-serif; font-size:16px; font-weight:bold;
                            color:#059669; letter-spacing:4px; margin-bottom:6px; text-transform:uppercase;">
                    International Academy
                </div>
                <div style="font-size:10px; color:#333; margin-bottom:4px; font-weight:500;">
                    House #9, Road #14, Sobhanbagh, Dhanmondi, Dhaka
                </div>
                <div style="font-size:9px; color:#333; display:flex; justify-content:center; gap:15px; flex-wrap:wrap;">
                    <span>Tel: +880 1729-649017</span>
                    <span>Web: www.alakhirahacademy.com</span>
                    <span>FB: /alakhirahacademy</span>
                </div>
            </div>
        </div>

        {{-- Receipt No & Date — top right --}}
        <div style="position:absolute; top:0; right:0; text-align:right;">
            <div style="font-size:11px; font-weight:bold; color:#059669; margin-top:5px;">
                Receipt No: {{ $receiptNumber }}
            </div>
            <div style="font-size:11px; font-weight:bold; color:#059669; margin-top:5px;">
                Date: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}
            </div>
        </div>
    </div>

    {{-- ── TITLE ── --}}
    <h1 style="text-align:center; font-size:18px; margin:10px 0; text-transform:uppercase;
               color:#059669; border-bottom:4px solid #059669; padding-bottom:5px;">
        PAYMENT RECEIPT
    </h1>

    {{-- ── BODY: Student Details + Fee Table ── --}}
    <div style="display:table; width:98%; margin-top:15px;">

        {{-- Student Details --}}
        <div style="display:table-cell; vertical-align:top; width:35%; padding-right:15px;">
            @php
                $details = [
                    'Student Name' => $student?->full_name ?? 'N/A',
                    'Student ID'   => $student?->student_id ?? 'N/A',
                    'Class'        => $student?->class?->name ?? 'N/A',
                    'Section'      => $student?->section?->name ?? 'N/A',
                    'Father Name'  => $student?->father_name ?? 'N/A',
                    'Mother Name'  => $student?->mother_name ?? 'N/A',
                    'Phone'        => $student?->guardian_phone ?? 'N/A',
                ];
            @endphp
            @foreach($details as $label => $value)
            <div style="font-size:12px; padding:3px 0; display:block;">
                <span style="font-family:'Century Gothic',sans-serif; color:#059669; font-weight:600; min-width:100px; display:inline-block;">
                    {{ $label }}:
                </span>
                <span style="color:#000; padding-left:3px;">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        {{-- Fee Table --}}
        <div style="display:table-cell; vertical-align:top; width:65%; padding-left:15px;">
            <table style="width:100%; border-collapse:collapse; margin-bottom:15px; font-size:12px;">
                <thead>
                    <tr>
                        <th style="background:#f0fdf4; padding:8px; text-align:left; font-weight:bold;
                                   border:1px solid #059669; color:#059669;">Description</th>
                        @if($showDiscount)
                            <th style="background:#f0fdf4; padding:8px; text-align:right; font-weight:bold;
                                       border:1px solid #059669; color:#059669;">Amount (Tk)</th>
                            <th style="background:#f0fdf4; padding:8px; text-align:right; font-weight:bold;
                                       border:1px solid #059669; color:#059669;">Discount</th>
                            <th style="background:#f0fdf4; padding:8px; text-align:right; font-weight:bold;
                                       border:1px solid #059669; color:#059669;">Net Payable</th>
                        @else
                            <th style="background:#f0fdf4; padding:8px; text-align:right; font-weight:bold;
                                       border:1px solid #059669; color:#059669;">Amount (Tk)</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($feeRows as $fee)
                    <tr>
                        <td style="padding:8px; border:1px solid #059669; background:white;">
                            {{ $fee['name'] }}
                        </td>
                        @if($showDiscount)
                            <td style="padding:8px; border:1px solid #059669; background:white; text-align:right;">
                                {{ number_format($fee['original_amount'], 2) }}
                            </td>
                            <td style="padding:8px; border:1px solid #059669; background:white; text-align:right;">
                                {{ number_format($fee['discount'], 2) }}
                            </td>
                            <td style="padding:8px; border:1px solid #059669; background:white; text-align:right; font-weight:500;">
                                {{ number_format($fee['amount'], 2) }}
                            </td>
                        @else
                            <td style="padding:8px; border:1px solid #059669; background:white; text-align:right; font-weight:500;">
                                {{ number_format($fee['amount'], 2) }}
                            </td>
                        @endif
                    </tr>
                    @endforeach

                    {{-- Empty filler rows (min 4 rows total) --}}
                    @for($i = 0; $i < max(0, 4 - count($feeRows)); $i++)
                    <tr style="height:35px; background:#f8fafc;">
                        <td style="border:1px solid #059669;">&nbsp;</td>
                        @if($showDiscount)
                            <td style="border:1px solid #059669;">&nbsp;</td>
                            <td style="border:1px solid #059669;">&nbsp;</td>
                        @endif
                        <td style="border:1px solid #059669;">&nbsp;</td>
                    </tr>
                    @endfor

                    {{-- Total Row --}}
                    <tr style="background:#f0fdf4; border-top:2px solid #059669; font-weight:bold; font-size:12px;">
                        <td style="padding:8px; border:1px solid #059669; text-align:right; font-weight:bold;">
                            Total:
                        </td>
                        @if($showDiscount)
                            <td style="padding:8px; border:1px solid #059669; text-align:right; font-weight:bold;">
                                {{ number_format($totalOriginal, 2) }}
                            </td>
                            <td style="padding:8px; border:1px solid #059669; text-align:right; font-weight:bold;">
                                {{ number_format($totalDiscount, 2) }}
                            </td>
                            <td style="padding:8px; border:1px solid #059669; text-align:right; font-weight:bold;">
                                {{ number_format($totalAmount, 2) }}
                            </td>
                        @else
                            <td style="padding:8px; border:1px solid #059669; text-align:right; font-weight:bold;">
                                {{ number_format($totalAmount, 2) }}
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>

            {{-- Payment Method / Note / In Words --}}
            <div style="font-size:12px; margin-bottom:6px;">
                <label style="font-weight:bold; color:#059669;">Payment Method:</label>
                {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Cash')) }}
            </div>

            @if($payment->remarks && $payment->remarks !== 'Migrated from old system')
            <div style="font-size:12px; margin-bottom:6px;">
                <label style="font-weight:bold; color:#059669;">Note:</label>
                {{ $payment->remarks }}
            </div>
            @endif

            <div style="font-size:12px; margin-bottom:6px;">
                <label style="font-weight:bold; color:#059669;">In Word:</label>
                {{ ucwords($amountInWords) }} Taka Only
            </div>
        </div>
    </div>

    {{-- ── ACCOUNTANT SIGNATURE ── --}}
    <div style="margin-top:40px; margin-bottom:20px;">
        <div style="border-top:1px solid #000; width:150px; text-align:center;
                    font-size:12px; margin-left:20px; padding-top:4px;">
            Accountant
        </div>
    </div>

    {{-- ── FOOTER LOGOS ── --}}
    <div style="margin-top:10px; padding-top:15px; border-top:1px solid #059669; text-align:center;">
        <div style="display:flex; justify-content:center; align-items:center; gap:15px; flex-wrap:wrap;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMzUiIHZpZXdCb3g9IjAgMCAxMjAgMzUiPgogIDxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMzUiIGZpbGw9IiMxZTI5M2IiIHJ4PSI0Ii8+CiAgPHRleHQgeD0iNjAiIHk9IjIyIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjExIiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCI+QVNMQUY8L3RleHQ+Cjwvc3ZnPg==" alt="Aslaf" style="height:35px; width:120px;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMTAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAxMTAgMzAiPgogIDxyZWN0IHdpZHRoPSIxMTAiIGhlaWdodD0iMzAiIGZpbGw9IiMwMDMzOTkiIHJ4PSI0Ii8+CiAgPHRleHQgeD0iNTUiIHk9IjEyIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjciIGZpbGw9IndoaXRlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtd2VpZ2h0PSJib2xkIj5CUklUSVNIPC90ZXh0PgogIDx0ZXh0IHg9IjU1IiB5PSIyMyIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1zaXplPSI3IiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCI+Q09VTkNJTDwvdGV4dD4KPC9zdmc+" alt="British Council" style="height:25px; width:auto;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMTAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAxMTAgMzAiPgogIDxyZWN0IHdpZHRoPSIxMTAiIGhlaWdodD0iMzAiIGZpbGw9IiNkNTAwMDAiIHJ4PSI0Ii8+CiAgPHRleHQgeD0iNTUiIHk9IjE5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjkiIGZpbGw9IndoaXRlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtd2VpZ2h0PSJib2xkIj5DQU1CUklER0U8L3RleHQ+Cjwvc3ZnPg==" alt="Cambridge" style="height:25px; width:auto;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI5MCIgaGVpZ2h0PSIzMCIgdmlld0JveD0iMCAwIDkwIDMwIj4KICA8cmVjdCB3aWR0aD0iOTAiIGhlaWdodD0iMzAiIGZpbGw9IiMwMDY2Y2MiIHJ4PSI0Ii8+CiAgPHRleHQgeD0iNDUiIHk9IjE5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjEwIiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCI+RURFWENFTDwvdGV4dD4KPC9zdmc+" alt="Edexcel" style="height:25px; width:auto;">
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI5MCIgaGVpZ2h0PSIzMCIgdmlld0JveD0iMCAwIDkwIDMwIj4KICA8cmVjdCB3aWR0aD0iOTAiIGhlaWdodD0iMzAiIGZpbGw9IiMwMGIwZjAiIHJ4PSI0Ii8+CiAgPHRleHQgeD0iNDUiIHk9IjE5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXNpemU9IjEwIiBmaWxsPSJ3aGl0ZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXdlaWdodD0iYm9sZCI+UEVBUlNPTjwvdGV4dD4KPC9zdmc+" alt="Pearson" style="height:25px; width:auto;">
        </div>
    </div>

</div>{{-- end #receipt-content --}}

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #receipt-content, #receipt-content * { visibility: visible; }
        #receipt-content {
            position: absolute;
            left: 0; top: 0;
            width: 100%;
            box-shadow: none;
            border: 1px solid #059669;
            max-width: 100%;
        }
        .no-print { display: none !important; }
    }

    @page {
        size: A4 landscape;
        margin: 10mm;
    }
</style>
@endpush

@endsection