<style>
    @page { size: A4; margin: 0; }

    .a4-container * { box-sizing: border-box; }

    .a4-page {
        width: 210mm;
        min-height: 297mm;
        background: white;
        margin: 0 auto;
        position: relative;
        padding: 0;
        font-family: 'DM Sans', Arial, sans-serif;
    }

    /* ── Header ─────────────────────────────────── */
    .form-header {
        position: relative;
        height: 120px;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        overflow: hidden;
    }
    .form-header::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .form-header::after {
        content: '';
        position: absolute;
        bottom: -20px; right: 100px;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        padding: 15px 30px;
        height: 100%;
    }
    .logo-container {
        width: 84px; height: 84px;
        background: white;
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .logo-container img { max-width:100%; max-height:100%; object-fit:contain; }
    .institution-info { flex:1; margin-left:18px; color:white; text-shadow:0 1px 3px rgba(0,0,0,0.25); }
    .institution-name  { font-size:24px; font-weight:800; margin-bottom:4px; letter-spacing:0.5px; }
    .institution-details { font-size:11px; opacity:0.9; line-height:1.7; }

    /* ── Form Title ──────────────────────────────── */
    .form-title-section {
        background: linear-gradient(to right, #f0fdf4, #dcfce7);
        padding: 14px 30px;
        border-bottom: 3px solid #059669;
        text-align: center;
    }
    .form-title        { font-size:20px; font-weight:800; color:#065f46; letter-spacing:2px; }
    .academic-year     { font-size:13px; color:#059669; font-weight:600; margin-top:4px; }

    /* ── Sections ────────────────────────────────── */
    .form-content { padding: 24px 30px 100px; }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #059669;
        margin-bottom: 12px;
        margin-top: 20px;
        padding: 6px 12px;
        background: #f0fdf4;
        border-left: 3px solid #059669;
        border-radius: 0 6px 6px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .section-title:first-child { margin-top: 0; }

    /* ── Grid ───────────────────────────────────── */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px 20px;
        margin-bottom: 8px;
    }
    .form-grid.full-width { grid-template-columns: 1fr; }
    .form-grid.three-col  { grid-template-columns: repeat(3, 1fr); }

    .form-field { display: flex; flex-direction: column; }
    .field-label {
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .field-value {
        font-size: 13px;
        color: #0f172a;
        padding: 7px 11px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        min-height: 34px;
        display: flex;
        align-items: center;
        font-weight: 500;
    }
    .field-value.empty { color: #94a3b8; font-style: italic; font-weight: 400; }

    /* ── Photo ──────────────────────────────────── */
    .photo-section {
        width: 130px; height: 160px;
        border: 2px dashed #a7f3d0;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        background: #f0fdf4; overflow: hidden;
    }
    .photo-section img { width:100%; height:100%; object-fit:cover; }

    /* ── Signature ──────────────────────────────── */
    .signature-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px dashed #e2e8f0;
    }
    .signature-box  { text-align: center; }
    .signature-line { border-top: 1.5px solid #334155; padding-top: 44px; margin-bottom: 6px; }
    .signature-label { font-size: 11px; font-weight: 600; color: #475569; }

    /* ── Footer ─────────────────────────────────── */
    .form-footer {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 14px 30px;
        background: #f0fdf4;
        border-top: 2px solid #a7f3d0;
    }

    @media print {
        .a4-page { box-shadow:none; margin:0; width:100%; }
        .no-print { display:none !important; }
    }
</style>

<div class="a4-page a4-container">

    {{-- ── HEADER ── --}}
    <div class="form-header">
        <div class="header-content">
            <div class="logo-container">
                @if(\App\Models\GeneralSetting::getValue('admission_form_logo'))
                    <img src="{{ \App\Models\GeneralSetting::getValue('admission_form_logo') }}" alt="Logo">
                @else
                    <img src="{{ asset('madrasa-logo.jpeg') }}" alt="Logo"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <div style="display:none;font-size:28px;font-weight:800;color:#059669;">E</div>
                @endif
            </div>
            <div class="institution-info">
                <div class="institution-name">
                    {{ \App\Models\GeneralSetting::getValue('admission_form_institution_name', config('app.name', 'Al-Akhirah Academy')) }}
                </div>
                <div class="institution-details">
                    <div>📍 {{ \App\Models\GeneralSetting::getValue('admission_form_institution_address', 'House #9, Road #14, Sobhanbagh, Dhanmondi, Dhaka') }}</div>
                    <div>
                        📞 {{ \App\Models\GeneralSetting::getValue('admission_form_phone', '+880 1729-649017') }}
                        &nbsp;|&nbsp; ✉️ {{ \App\Models\GeneralSetting::getValue('admission_form_email', 'info@alakhirahacademy.com') }}
                        &nbsp;|&nbsp; 🌐 {{ \App\Models\GeneralSetting::getValue('admission_form_website', 'www.alakhirahacademy.com') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TITLE ── --}}
    <div class="form-title-section">
        <div class="form-title">
            {{ \App\Models\GeneralSetting::getValue('admission_form_title', 'STUDENT ADMISSION FORM') }}
        </div>
        <div class="academic-year">
            Academic Year: {{ \App\Models\GeneralSetting::getValue('admission_form_academic_year', date('Y').'-'.(date('Y')+1)) }}
        </div>
    </div>

    {{-- ── CONTENT ── --}}
    <div class="form-content">

        {{-- Personal Information --}}
        <div class="section-title">📋 Personal Information</div>
        <div style="display:flex; gap:20px; margin-bottom:8px;">
            <div style="flex:1;">
                <div class="form-grid">
                    <div class="form-field">
                        <div class="field-label">Student Name</div>
                        <div class="field-value">{{ trim(($student->first_name ?? '').' '.($student->last_name ?? '')) ?: 'N/A' }}</div>
                    </div>
                    <div class="form-field">
                        <div class="field-label">Date of Birth</div>
                        <div class="field-value">
                            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M, Y') : 'N/A' }}
                        </div>
                    </div>
                    <div class="form-field">
                        <div class="field-label">Gender</div>
                        <div class="field-value">{{ ucfirst($student->gender ?? 'N/A') }}</div>
                    </div>
                    <div class="form-field">
                        <div class="field-label">Blood Group</div>
                        <div class="field-value">{{ $student->blood_group ?? 'N/A' }}</div>
                    </div>
                    <div class="form-field">
                        <div class="field-label">Religion</div>
                        <div class="field-value">{{ $student->religion ?? 'N/A' }}</div>
                    </div>
                    <div class="form-field">
                        <div class="field-label">Nationality</div>
                        <div class="field-value">{{ $student->nationality ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            {{-- Photo --}}
            <div>
                <div class="photo-section">
                    @if($student->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}" alt="Student Photo">
                    @else
                        <div style="text-align:center;color:#a7f3d0;font-size:11px;padding:10px;">
                            <div style="font-size:32px;margin-bottom:6px;">📷</div>
                            Student<br>Photo
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="section-title">📞 Contact Information</div>
        <div class="form-grid">
            <div class="form-field">
                <div class="field-label">Phone Number</div>
                <div class="field-value">{{ $student->phone ?? $student->guardian_phone ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Email Address</div>
                <div class="field-value">{{ $student->email ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="form-grid full-width">
            <div class="form-field">
                <div class="field-label">Present Address</div>
                <div class="field-value">{{ $student->address ?? 'N/A' }}</div>
            </div>
        </div>

        {{-- Academic Information --}}
        <div class="section-title">🎓 Academic Information</div>
        <div class="form-grid three-col">
            <div class="form-field">
                <div class="field-label">Class</div>
                <div class="field-value">{{ $student->class?->name ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Section</div>
                <div class="field-value">{{ $student->section?->name ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Student ID</div>
                <div class="field-value" style="color:#059669;font-weight:700;">{{ $student->student_id ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Admission Date</div>
                <div class="field-value">
                    {{ $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('d M, Y') : 'N/A' }}
                </div>
            </div>
            <div class="form-field">
                <div class="field-label">Program Type</div>
                <div class="field-value">{{ $student->program_type ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Shift</div>
                <div class="field-value">{{ ucfirst($student->shift ?? 'N/A') }}</div>
            </div>
        </div>

        {{-- Guardian Information --}}
        <div class="section-title">👨‍👩‍👧 Guardian Information</div>
        <div class="form-grid">
            <div class="form-field">
                <div class="field-label">Father's Name</div>
                <div class="field-value">{{ $student->father_name ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Mother's Name</div>
                <div class="field-value">{{ $student->mother_name ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Guardian Phone</div>
                <div class="field-value">{{ $student->guardian_phone ?? 'N/A' }}</div>
            </div>
            <div class="form-field">
                <div class="field-label">Mother's Mobile</div>
                <div class="field-value">{{ $student->mother_mobile ?? 'N/A' }}</div>
            </div>
        </div>

        {{-- Signature --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Student's Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Guardian's Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Principal's Signature</div>
            </div>
        </div>

    </div>{{-- end form-content --}}

    {{-- ── FOOTER ── --}}
    <div class="form-footer">
        <div style="text-align:center; font-size:10px; color:#064e3b;">
            <strong>Note:</strong> This is a computer-generated admission form. Please verify all information before submission.
            &nbsp;|&nbsp; Generated: {{ now()->format('d M, Y h:i A') }}
        </div>
    </div>

</div>{{-- end a4-page --}}