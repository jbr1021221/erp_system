@extends('layouts.app')
@section('page-title', 'Collect Fee')
@section('breadcrumb', 'Finance · Income · Collect')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Collect Fee</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Record a new fee payment for a student</p>
  </div>
  <a href="{{ route('payments.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-secondary);text-decoration:none;">
    ← Back to Payments
  </a>
</div>

<form method="POST" action="{{ route('payments.store') }}" id="paymentForm">
  @csrf
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Form (2/3) --}}
    <div class="lg:col-span-2 flex flex-col gap-5">

      {{-- STEP 1: Class → Section → Student --}}
      <div class="card p-6 animate-in delay-1">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Select Student</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

          {{-- Class --}}
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Class *</label>
            <select id="filterClass"
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;cursor:pointer;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <option value="">— Select Class —</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- Section --}}
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Section *</label>
            <select id="filterSection" disabled
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;cursor:pointer;opacity:0.5;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <option value="">— Select Section —</option>
            </select>
          </div>

          {{-- Student --}}
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Student *</label>
            <select id="filterStudent" name="student_id" required disabled
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;cursor:pointer;opacity:0.5;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <option value="">— Select Student —</option>
            </select>
            @error('student_id')<p style="font-size:11px;color:var(--accent);margin-top:4px;">{{ $message }}</p>@enderror
          </div>

        </div>

        {{-- Selected Student Info Card --}}
        <div id="studentInfoCard" style="display:none;margin-top:16px;padding:14px 16px;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:12px;">
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div>
              <p style="font-size:11px;color:var(--text-muted);">Name</p>
              <p id="infoName" style="font-size:13px;font-weight:600;color:var(--text-primary);"></p>
            </div>
            <div>
              <p style="font-size:11px;color:var(--text-muted);">Student ID</p>
              <p id="infoId" style="font-size:13px;font-weight:600;color:var(--accent);"></p>
            </div>
            <div>
              <p style="font-size:11px;color:var(--text-muted);">Class</p>
              <p id="infoClass" style="font-size:13px;font-weight:600;color:var(--text-primary);"></p>
            </div>
            <div>
              <p style="font-size:11px;color:var(--text-muted);">Section</p>
              <p id="infoSection" style="font-size:13px;font-weight:600;color:var(--text-primary);"></p>
            </div>
          </div>
        </div>
      </div>

      {{-- STEP 2: Fee Items --}}
      <div class="card p-6 animate-in delay-2" id="feeSection" style="display:none;">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent-green);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Fee Items</h2>
          <span id="feeLoadingSpinner" style="display:none;font-size:12px;color:var(--text-muted);">Loading...</span>
        </div>

        {{-- Fee items will be injected here --}}
        <div id="feeItemsContainer"></div>

        <p id="noFeesMsg" style="display:none;font-size:13px;color:var(--text-muted);text-align:center;padding:20px 0;">
          ✅ All fees are paid for this student.
        </p>
      </div>

      {{-- STEP 3: Payment Details --}}
      <div class="card p-6 animate-in delay-3" id="paymentDetailsSection" style="display:none;">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent-info);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Payment Details</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Payment Method *</label>
            <select name="payment_method" required
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;cursor:pointer;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <option value="">Select Method</option>
              <option value="cash"          {{ old('payment_method')=='cash'          ? 'selected' : '' }}>Cash</option>
              <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
              <option value="online"        {{ old('payment_method')=='online'        ? 'selected' : '' }}>Mobile Banking (bKash/Nagad)</option>
              <option value="cheque"        {{ old('payment_method')=='cheque'        ? 'selected' : '' }}>Cheque</option>
            </select>
          </div>
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Payment Date *</label>
            <input type="date" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}"
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
          </div>
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Transaction Reference</label>
            <input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}" placeholder="Optional"
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
          </div>
          <div>
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Billing Year</label>
            <input type="text" name="billing_year" value="{{ old('billing_year', date('Y')) }}" maxlength="4"
              style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
          </div>
        </div>
        <div class="mt-4">
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Remarks</label>
          <textarea name="remarks" rows="2" placeholder="Optional notes..."
            style="width:100%;padding:12px 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;resize:none;"
            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ old('remarks') }}</textarea>
        </div>
      </div>

    </div>

    {{-- Sidebar (1/3) --}}
    <div class="flex flex-col gap-5">

      {{-- Payment Summary --}}
      <div class="card p-6 animate-in delay-1" style="border-top:3px solid var(--accent-green);">
        <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);margin-bottom:16px;">Payment Summary</h3>
        <div class="flex flex-col gap-3">
          <div class="flex justify-between items-center">
            <span style="font-size:13px;color:var(--text-secondary);">Items Selected</span>
            <span id="summaryCount" style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">0</span>
          </div>
          <div style="height:1px;background:var(--border-color);"></div>
          <div class="flex justify-between items-center">
            <span style="font-size:14px;font-weight:600;color:var(--text-primary);">Total Amount</span>
            <span id="summaryTotal" style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:var(--accent-green);">৳0</span>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="card p-5 animate-in delay-2">
        <button type="submit"
          class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl py-3 px-4 font-bold text-[15px] transition-colors mb-3">
          Record Payment
        </button>
        <a href="{{ route('payments.index') }}"
           style="display:block;text-align:center;height:40px;line-height:40px;border-radius:10px;font-size:13px;color:var(--text-muted);text-decoration:none;">
          Cancel
        </a>
      </div>

    </div>
  </div>
</form>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(function () {

    // ── Data from server ─────────────────────────────────────────────────
    const allStudents = {!! json_encode($studentsJson) !!};
    const allSections = {!! json_encode($sectionsJson) !!};

    // ── Helpers ───────────────────────────────────────────────────────────
    function enableSelect($el) {
        $el.prop('disabled', false).css('opacity', '1');
    }
    function disableSelect($el, placeholder) {
        $el.prop('disabled', true).css('opacity', '0.5')
           .html(`<option value="">${placeholder}</option>`);
    }
    function hideFeeSection() {
        $('#feeSection, #paymentDetailsSection').hide();
        $('#feeItemsContainer').empty();
        $('#noFeesMsg').hide();
        updateSummary();
    }
    function formatFreq(f) {
        return {monthly:'Monthly', quarterly:'Quarterly', half_yearly:'Half-Yearly',
                yearly:'Yearly', one_time:'One Time'}[f] ?? f;
    }
    function updateSummary() {
        let total = 0, count = 0;
        $('.fee-checkbox:checked').each(function () {
            const idx = $(this).data('idx');
            const amt = parseFloat($(`.fee-amount[data-idx="${idx}"]`).val()) || 0;
            total += amt;
            count++;
        });
        $('#summaryCount').text(count);
        $('#summaryTotal').text('৳' + total.toLocaleString());
    }

    // ── Class → Sections ─────────────────────────────────────────────────
    $('#filterClass').on('change', function () {
        const classId = parseInt($(this).val());

        disableSelect($('#filterSection'), '— Select Section —');
        disableSelect($('#filterStudent'), '— Select Student —');
        $('#studentInfoCard').hide();
        hideFeeSection();

        if (!classId) return;

        const sections = allSections.filter(s => s.class_id == classId);
        const $sec = $('#filterSection').empty().append('<option value="">— Select Section —</option>');
        $.each(sections, (_, s) => $sec.append(`<option value="${s.id}">${s.name}</option>`));
        enableSelect($sec);
    });

    // ── Section → Students ───────────────────────────────────────────────
    $('#filterSection').on('change', function () {
        const sectionId = parseInt($(this).val());
        const classId   = parseInt($('#filterClass').val());

        disableSelect($('#filterStudent'), '— Select Student —');
        $('#studentInfoCard').hide();
        hideFeeSection();

        if (!sectionId) return;

        const students = allStudents.filter(s => s.class_id == classId && s.section_id == sectionId);
        const $stu = $('#filterStudent').empty().append('<option value="">— Select Student —</option>');
        $.each(students, (_, s) => $stu.append(`<option value="${s.id}">${s.name} (${s.student_id})</option>`));
        enableSelect($stu);
    });

    // ── Student → Load Fees (AJAX) ────────────────────────────────────────
    $('#filterStudent').on('change', function () {
        const studentId = $(this).val();

        hideFeeSection();
        $('#studentInfoCard').hide();

        if (!studentId) return;

        // Show student info card
        const student = allStudents.find(s => s.id == studentId);
        if (student) {
            $('#infoName').text(student.name);
            $('#infoId').text(student.student_id);
            $('#infoClass').text(student.class_name);
            $('#infoSection').text(student.section_name);
            $('#studentInfoCard').show();
        }

        // Load fees via AJAX
        $('#feeSection').show();
        $('#feeLoadingSpinner').show();
        $('#feeItemsContainer').empty();
        $('#noFeesMsg').hide();

        $.ajax({
            url: '/payments/payable-fees',
            data: { student_id: studentId },
            success: function (fees) {
                $('#feeLoadingSpinner').hide();
                if (!fees || fees.length === 0) {
                    $('#noFeesMsg').show();
                    $('#paymentDetailsSection').hide();
                    return;
                }
                renderFeeItems(fees);
                $('#paymentDetailsSection').show();
                updateSummary();
            },
            error: function () {
                $('#feeLoadingSpinner').hide();
                $('#feeItemsContainer').html('<p style="color:var(--accent);font-size:13px;">Failed to load fees. Please try again.</p>');
            }
        });
    });

    // ── Render Fee Item Rows ──────────────────────────────────────────────
    function renderFeeItems(fees) {
        const $container = $('#feeItemsContainer').empty();

        $.each(fees, function (fIdx, fee) {
            const periodOptions = $.map(fee.available_periods, p =>
                `<option value="${p.value}">${p.label}</option>`
            ).join('');

            const $row = $(`
            <div class="fee-item-row" style="padding:14px;margin-bottom:10px;background:var(--bg-surface-2);border:1px solid var(--border-color);border-radius:12px;transition:all 0.2s;">
                <input type="hidden" name="items[${fIdx}][fee_structure_id]" value="${fee.id}">
                <input type="hidden" name="items[${fIdx}][fee_type]"         value="${fee.fee_type}">

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    <div class="sm:col-span-1 flex items-center gap-3">
                        <input type="checkbox" class="fee-checkbox" data-idx="${fIdx}" data-amount="${fee.unit_amount}"
                            style="width:18px;height:18px;accent-color:var(--accent-green);cursor:pointer;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:var(--text-primary);">${fee.fee_type}</p>
                            <p style="font-size:11px;color:var(--text-muted);">${formatFreq(fee.frequency)}</p>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:4px;">Period</label>
                        <select class="fee-period" data-idx="${fIdx}" disabled
                            style="width:100%;height:42px;padding:0 10px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;opacity:0.4;">
                            ${periodOptions}
                        </select>
                    </div>
                    <div>
                        <label style="font-size:11px;color:var(--text-muted);display:block;margin-bottom:4px;">Amount (৳)</label>
                        <input type="number" class="fee-amount" data-idx="${fIdx}"
                            value="${fee.unit_amount}" min="0" step="1" disabled
                            style="width:100%;height:42px;padding:0 10px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;opacity:0.4;">
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:11px;color:var(--text-muted);">Standard</p>
                        <p style="font-size:14px;font-weight:700;color:var(--text-primary);">৳${Number(fee.unit_amount).toLocaleString()}</p>
                    </div>
                </div>
            </div>`);

            $container.append($row);
        });
    }

    // ── Toggle Fee Row (checkbox) ─────────────────────────────────────────
    $(document).on('change', '.fee-checkbox', function () {
        const $row   = $(this).closest('.fee-item-row');
        const $period = $row.find('.fee-period');
        const $amount = $row.find('.fee-amount');

        if ($(this).is(':checked')) {
            $period.prop('disabled', false).css('opacity', '1');
            $amount.prop('disabled', false).css('opacity', '1');
            $row.css({ borderColor: 'var(--accent-green)', background: 'rgba(5,150,105,0.05)' });
        } else {
            $period.prop('disabled', true).css('opacity', '0.4');
            $amount.prop('disabled', true).css('opacity', '0.4');
            $row.css({ borderColor: 'var(--border-color)', background: 'var(--bg-surface-2)' });
        }
        updateSummary();
    });

    // ── Amount input → update summary ────────────────────────────────────
    $(document).on('input', '.fee-amount', function () {
        updateSummary();
    });

    // ── Form submit: fix names & re-enable disabled inputs ───────────────
    $('#paymentForm').on('submit', function () {
        // Remove unchecked rows from form
        $('.fee-checkbox:not(:checked)').closest('.fee-item-row')
            .find('input, select').removeAttr('name');

        // Re-enable checked row inputs so they submit
        $('.fee-checkbox:checked').closest('.fee-item-row')
            .find('.fee-period, .fee-amount').prop('disabled', false);

        // Re-index items[] sequentially
        let newIdx = 0;
        $('.fee-item-row').each(function () {
            const $feeId  = $(this).find('input[type=hidden][name*=fee_structure_id]');
            if (!$feeId.attr('name')) return; // was unchecked, skip
            $feeId.attr('name', `items[${newIdx}][fee_structure_id]`);
            $(this).find('input[type=hidden][name*=fee_type]').attr('name', `items[${newIdx}][fee_type]`);
            $(this).find('.fee-period').attr('name', `items[${newIdx}][billing_month]`);
            $(this).find('.fee-amount').attr('name', `items[${newIdx}][amount]`);
            newIdx++;
        });
    });

});
</script>
@endpush

@endsection