<style>
    @page {
        size: landscape;
        margin: 0;
    }
    
    .receipt-container {
        font-family: 'Arial', sans-serif;
        background: white;
        width: 100%;
        max-width: 250mm; /* Wide enough for landscape */
        margin: 0 auto;
        border: 2px solid #333;
        padding: 20px;
        position: relative;
    }
    
    .receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .receipt-logo img {
        height: 60px;
        width: auto;
    }
    
    .receipt-institution {
        text-align: center;
        flex: 1;
        padding: 0 20px;
    }
    
    .receipt-institution h1 {
        font-size: 24px;
        margin: 0;
        color: #2d3748;
        text-transform: uppercase;
    }
    
    .receipt-institution p {
        font-size: 12px;
        color: #4a5568;
        margin: 5px 0 0;
    }
    
    .receipt-title {
        text-align: right;
    }
    
    .receipt-copy {
        font-size: 12px;
        font-weight: bold;
        background: #333;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 5px;
    }
    
    .receipt-id {
        font-size: 14px;
        font-weight: bold;
        color: #e53e3e;
    }
    
    .receipt-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
        background: #f7fafc;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    
    .info-item label {
        display: block;
        font-size: 11px;
        color: #718096;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    
    .info-item span {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
    }
    
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    
    .receipt-table th {
        background: #edf2f7;
        padding: 10px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #4a5568;
        border-bottom: 2px solid #cbd5e0;
    }
    
    .receipt-table td {
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
        color: #2d3748;
    }
    
    .receipt-total {
        margin-top: 20px;
        text-align: right;
    }
    
    .receipt-total-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .receipt-total-label {
        font-size: 13px;
        color: #718096;
        margin-right: 20px;
    }
    
    .receipt-total-amount {
        font-size: 16px;
        font-weight: bold;
        color: #2d3748;
        width: 120px;
    }
    
    .grand-total .receipt-total-amount {
        font-size: 20px;
        color: #2b6cb0;
        border-top: 2px solid #2b6cb0;
        padding-top: 5px;
    }
    
    .amount-words {
        margin-top: 10px;
        font-style: italic;
        font-size: 12px;
        color: #718096;
        text-align: left;
    }
    
    .receipt-footer {
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
    }
    
    .footer-sig {
        text-align: center;
        width: 200px;
    }
    
    .footer-line {
        border-top: 1px solid #cbd5e0;
        margin-bottom: 5px;
    }
    
    .footer-label {
        font-size: 11px;
        color: #718096;
    }
    
    /* Print specific adjustments */
    @media print {
        .receipt-container {
            border: none;
            max-width: none;
            width: 100%;
        }
        
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="receipt-container">
    <!-- Header -->
    <div class="receipt-header">
        <div class="receipt-logo">
            @if(\App\Models\GeneralSetting::getValue('admission_form_logo'))
            <img src="{{ \App\Models\GeneralSetting::getValue('admission_form_logo') }}" alt="Logo">
            @else
            <div style="font-size: 30px; font-weight: bold; color: #333;">LOGO</div>
            @endif
        </div>
        
        <div class="receipt-institution">
            <h1>{{ \App\Models\GeneralSetting::getValue('admission_form_institution_name', 'Institution Name') }}</h1>
            <p>{{ \App\Models\GeneralSetting::getValue('admission_form_address', 'Address Line 1, City') }} | {{ \App\Models\GeneralSetting::getValue('admission_form_phone', 'Phone Number') }}</p>
        </div>
        
        <div class="receipt-title">
            <div class="receipt-copy">STUDENT COPY</div>
            <div class="receipt-id">Receipt #: {{ $payment->payment_id ?? str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div style="font-size: 11px; color: #718096;">Rank: {{ \App\Models\GeneralSetting::getValue('current_session', date('Y')) }}</div>
        </div>
    </div>
    
    <!-- Info Grid -->
    <div class="receipt-info-grid">
        <div class="info-item">
            <label>Student Name</label>
            <span>{{ $student->full_name }}</span>
        </div>
        <div class="info-item">
            <label>Student ID</label>
            <span>{{ $student->student_id }}</span>
        </div>
        <div class="info-item">
            <label>Class/Section</label>
            <span>{{ $student->class->name ?? 'N/A' }} {{ $student->section ? '('.$student->section->name.')' : '' }}</span>
        </div>
        <div class="info-item">
            <label>Date</label>
            <span>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</span>
        </div>
        <div class="info-item">
            <label>Payment Method</label>
            <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
        </div>
        <div class="info-item">
            <label>Received By</label>
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>
    
    <!-- Fees Table -->
    <table class="receipt-table">
        <thead>
            <tr>
                <th style="width: 50%;">Fee Description</th>
                <th style="text-align: right;">Amount</th>
                <th style="text-align: right;">Discount</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                // If payment is linked to a specific fee structure, show that details
                $feeName = $payment->feeStructure ? $payment->feeStructure->fee_type : ($payment->notes ?? 'Admission Fee');
                
                // Calculate discount if applicable (this is tricky as payment table stores net amount)
                // We'll estimate based on assigned fees if matched
                $assignment = $student->feeAssignments->where('fee_structure_id', $payment->fee_structure_id)->first();
                $discount = 0;
                $originalAmount = $payment->amount;
                
                if ($assignment) {
                    $discount = $assignment->getDiscountAmount();
                    $originalAmount += $discount;
                    
                    // If it's partial payment, we just show the paid amount for now
                    // But usually receipts show what was paid.
                }
            @endphp
            <tr>
                <td>
                    {{ $feeName }}
                    @if($payment->notes)
                    <br><span style="font-size: 10px; color: #718096;">{{ $payment->notes }}</span>
                    @endif
                </td>
                <td style="text-align: right;">{{ number_format($payment->amount + $discount, 2) }}</td>
                <td style="text-align: right;">{{ number_format($discount, 2) }}</td>
                <td style="text-align: right;">{{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Totals -->
    <div class="receipt-total">
        <div class="receipt-total-row">
            <span class="receipt-total-label">Subtotal:</span>
            <span class="receipt-total-amount">{{ number_format($payment->amount + $discount, 2) }}</span>
        </div>
        <div class="receipt-total-row">
            <span class="receipt-total-label">Total Discount:</span>
            <span class="receipt-total-amount" style="color: #e53e3e;">-{{ number_format($discount, 2) }}</span>
        </div>
        <div class="receipt-total-row grand-total">
            <span class="receipt-total-label">Net Paid:</span>
            <span class="receipt-total-amount">৳{{ number_format($payment->amount, 2) }}</span>
        </div>
        
        <!-- Amount in Words (Placeholder function or hardcoded for now) -->
        <div class="amount-words">
            In Words: {{-- Convert number to words here if needed --}} Taka Only
        </div>
    </div>
    
    <!-- Footer Signatures -->
    <div class="receipt-footer">
        <div class="footer-sig">
            <div class="footer-line"></div>
            <div class="footer-label">Student/Guardian Signature</div>
        </div>
        <div class="footer-sig">
            <div class="footer-line"></div>
            <div class="footer-label">Authorized Signature</div>
        </div>
    </div>
</div>
