@php
    // ── Amount in words ───────────────────────────────────────────────────
    function admRecNumberToWords(int $n): string {
        $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
                 'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
                 'Seventeen','Eighteen','Nineteen'];
        $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
        if ($n === 0) return 'Zero';
        $w = '';
        if ($n >= 100000) { $w .= admRecNumberToWords((int)($n/100000)).' Lakh '; $n %= 100000; }
        if ($n >= 1000)   { $w .= admRecNumberToWords((int)($n/1000)).' Thousand '; $n %= 1000; }
        if ($n >= 100)    { $w .= $ones[(int)($n/100)].' Hundred '; $n %= 100; }
        if ($n >= 20)     { $w .= $tens[(int)($n/10)].' '; $n %= 10; }
        if ($n > 0)       { $w .= $ones[$n].' '; }
        return trim($w);
    }

    $receiptPayment = $payment ?? $student->payments()->latest()->first();
    $receiptAmount  = $receiptPayment?->amount ?? 0;
    $receiptNo      = $receiptPayment?->receipt_number ?? ('ADM-'.date('Ymd').'-'.strtoupper(substr(uniqid(),0,6)));
    $receiptDate    = $receiptPayment ? \Carbon\Carbon::parse($receiptPayment->payment_date)->format('d M, Y') : now()->format('d M, Y');
    $receiptMethod  = ucfirst(str_replace('_',' ', $receiptPayment?->payment_method ?? 'Cash'));
    $receivedByName = $receiptPayment?->receivedBy?->name ?? auth()->user()?->name ?? 'Admin';
    $amountWords    = ucwords(admRecNumberToWords((int)$receiptAmount));

    // Fee rows — all payments sharing same receipt_number
    $feeRows = collect();
    if ($receiptPayment && $receiptPayment->receipt_number) {
        $feeRows = \App\Models\Payment::where('receipt_number', $receiptPayment->receipt_number)->get();
    }
    if ($feeRows->isEmpty() && $receiptPayment) {
        $feeRows = collect([$receiptPayment]);
    }

    $totalAmount   = $feeRows->sum('amount');
    $amountWords   = ucwords(admRecNumberToWords((int)$totalAmount));
@endphp

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- MODERN RECEIPT — matches ERP emerald green theme                    --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="admission-receipt" style="
    max-width: 860px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    font-family: 'DM Sans', Arial, sans-serif;
    font-size: 13px;
    color: #0f172a;
">

    {{-- ── TOP HEADER BAR ────────────────────────────────────────────── --}}
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 28px 32px; color: white; position: relative; overflow: hidden;">
        {{-- decorative circle --}}
        <div style="position:absolute;top:-30px;right:-30px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
        <div style="position:absolute;bottom:-20px;right:80px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>

        <div style="display:flex; justify-content:space-between; align-items:flex-start; position:relative;">
            {{-- Left: Academy info --}}
            <div>
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:10px;">
                    <div style="width:52px;height:52px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <img src="{{ asset('madrasa-logo.jpeg') }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                             style="width:44px;height:44px;object-fit:contain;border-radius:8px;">
                        <span style="display:none;color:white;font-size:20px;font-weight:800;">E</span>
                    </div>
                    <div>
                        <p style="font-size:18px;font-weight:800;margin:0;letter-spacing:0.5px;">
                            {{ config('app.name', 'Al-Akhirah Academy') }}
                        </p>
                        <p style="font-size:11px;opacity:0.8;margin:2px 0 0;">
                            House #9, Road #14, Sobhanbagh, Dhanmondi, Dhaka
                        </p>
                    </div>
                </div>
                <p style="font-size:11px;opacity:0.75;margin:0;">
                    Tel: +880 1729-649017 &nbsp;|&nbsp; www.alakhirahacademy.com
                </p>
            </div>

            {{-- Right: Receipt badge --}}
            <div style="text-align:right;">
                <div style="background:rgba(255,255,255,0.15);backdrop-filter:blur(4px);border:1px solid rgba(255,255,255,0.25);border-radius:12px;padding:12px 20px;">
                    <p style="font-size:10px;text-transform:uppercase;letter-spacing:2px;opacity:0.8;margin:0 0 4px;">Payment Receipt</p>
                    <p style="font-size:16px;font-weight:800;margin:0;letter-spacing:0.5px;">{{ $receiptNo }}</p>
                    <p style="font-size:11px;opacity:0.8;margin:4px 0 0;">{{ $receiptDate }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STUDENT INFO ROW ───────────────────────────────────────────── --}}
    <div style="background:#f0fdf4; padding:16px 32px; border-bottom:1px solid #d1fae5;">
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
            <div>
                <p style="font-size:10px;color:#059669;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin:0 0 3px;">Student Name</p>
                <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">{{ $student->full_name ?? ($student->first_name.' '.$student->last_name) }}</p>
            </div>
            <div>
                <p style="font-size:10px;color:#059669;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin:0 0 3px;">Student ID</p>
                <p style="font-size:14px;font-weight:700;color:#059669;margin:0;">{{ $student->student_id ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size:10px;color:#059669;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin:0 0 3px;">Class / Section</p>
                <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">
                    {{ $student->class?->name ?? 'N/A' }}
                    @if($student->section?->name) <span style="color:#64748b;">({{ $student->section->name }})</span> @endif
                </p>
            </div>
            <div>
                <p style="font-size:10px;color:#059669;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin:0 0 3px;">Payment Method</p>
                <p style="font-size:14px;font-weight:700;color:#0f172a;margin:0;">{{ $receiptMethod }}</p>
            </div>
        </div>
    </div>

    {{-- ── FEE TABLE ──────────────────────────────────────────────────── --}}
    <div style="padding: 24px 32px;">
        <table style="width:100%; border-collapse:collapse; margin-bottom:0;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid #e2e8f0;">#</th>
                    <th style="padding:10px 14px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid #e2e8f0;">Fee Description</th>
                    <th style="padding:10px 14px; text-align:center; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid #e2e8f0;">Period</th>
                    <th style="padding:10px 14px; text-align:right; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid #e2e8f0;">Amount (Tk)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feeRows as $idx => $row)
                <tr style="border-bottom:1px solid #f1f5f9; {{ $loop->even ? 'background:#fafafa;' : '' }}">
                    <td style="padding:12px 14px; color:#94a3b8; font-size:12px;">{{ $idx + 1 }}</td>
                    <td style="padding:12px 14px;">
                        <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">{{ $row->fee_type }}</p>
                    </td>
                    <td style="padding:12px 14px; text-align:center;">
                        @if($row->billing_month)
                            <span style="display:inline-block;padding:3px 10px;background:#d1fae5;color:#065f46;font-size:11px;font-weight:600;border-radius:20px;">
                                {{ DateTime::createFromFormat('!m', $row->billing_month)->format('F') }} {{ $row->billing_year }}
                            </span>
                        @else
                            <span style="display:inline-block;padding:3px 10px;background:#f1f5f9;color:#475569;font-size:11px;font-weight:600;border-radius:20px;">
                                {{ $row->billing_year ?? date('Y') }}
                            </span>
                        @endif
                    </td>
                    <td style="padding:12px 14px; text-align:right; font-size:13px; font-weight:700; color:#0f172a;">
                        {{ number_format($row->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:20px; text-align:center; color:#94a3b8; font-size:13px;">No fee details found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- ── TOTAL SECTION ────────────────────────────────────────── --}}
        <div style="margin-top:0; border-top:2px solid #059669; padding-top:16px; display:flex; justify-content:space-between; align-items:flex-end;">

            {{-- Left: In words + received by --}}
            <div style="max-width:55%;">
                <div style="background:#f0fdf4;border:1px solid #a7f3d0;border-radius:10px;padding:12px 16px;margin-bottom:12px;">
                    <p style="font-size:10px;color:#059669;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin:0 0 3px;">Amount in Words</p>
                    <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">{{ $amountWords }} Taka Only</p>
                </div>
                <p style="font-size:11px;color:#94a3b8;margin:0;">
                    Received by: <span style="color:#475569;font-weight:600;">{{ $receivedByName }}</span>
                    &nbsp;|&nbsp; Date: <span style="color:#475569;font-weight:600;">{{ $receiptDate }}</span>
                </p>
            </div>

            {{-- Right: Total box --}}
            <div style="text-align:right;">
                <div style="background: linear-gradient(135deg,#059669,#047857); color:white; border-radius:12px; padding:16px 24px; min-width:180px;">
                    <p style="font-size:11px;opacity:0.85;text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">Total Paid</p>
                    <p style="font-size:26px;font-weight:800;margin:0;">৳{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- ── SIGNATURE ROW ────────────────────────────────────────── --}}
        <div style="margin-top:32px; display:flex; justify-content:space-between; padding-top:20px; border-top:1px dashed #e2e8f0;">
            <div style="text-align:center; min-width:160px;">
                <div style="height:1px;background:#94a3b8;margin-bottom:6px;"></div>
                <p style="font-size:11px;color:#64748b;margin:0;">Student / Guardian Signature</p>
            </div>
            <div style="text-align:center; min-width:160px;">
                <div style="height:1px;background:#94a3b8;margin-bottom:6px;"></div>
                <p style="font-size:11px;color:#64748b;margin:0;">Authorized Signature</p>
            </div>
        </div>
    </div>

    {{-- ── FOOTER ─────────────────────────────────────────────────────── --}}
    <div style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:12px 32px; display:flex; justify-content:space-between; align-items:center;">
        <p style="font-size:10px;color:#94a3b8;margin:0;">This is a computer-generated receipt and does not require a physical stamp.</p>
        <p style="font-size:10px;color:#94a3b8;margin:0;">Generated: {{ now()->format('d M, Y h:i A') }}</p>
    </div>

</div>

@push('styles')
<style>
@media print {
    #admission-receipt {
        box-shadow: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
    }
}
</style>
@endpush