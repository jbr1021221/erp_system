    <style>
        /* A4 Page Setup */
        @page {
            size: A4;
            margin: 0;
        }
        
        .a4-container * {
            box-sizing: border-box;
        }
        
        /* A4 Container - 210mm x 297mm */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            position: relative;
            padding: 0;
        }
        
        /* Header with Banner */
        .form-header {
            position: relative;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }
        
        .header-banner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.3;
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
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .institution-info {
            flex: 1;
            margin-left: 20px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .institution-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .institution-details {
            font-size: 12px;
            opacity: 0.95;
            line-height: 1.6;
        }
        
        .institution-details div {
            margin-bottom: 2px;
        }
        
        /* Form Title */
        .form-title-section {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            padding: 15px 30px;
            border-bottom: 3px solid #667eea;
        }
        
        .form-title {
            font-size: 22px;
            font-weight: bold;
            color: #2d3748;
            text-align: center;
            letter-spacing: 2px;
        }
        
        .academic-year {
            text-align: center;
            font-size: 14px;
            color: #667eea;
            font-weight: 600;
            margin-top: 5px;
        }
        
        /* Form Content */
        .form-content {
            padding: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px 20px;
            margin-bottom: 25px;
        }
        
        .form-grid.full-width {
            grid-template-columns: 1fr;
        }
        
        .form-field {
            display: flex;
            flex-direction: column;
        }
        
        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .field-value {
            font-size: 14px;
            color: #1a202c;
            padding: 8px 12px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            min-height: 36px;
            display: flex;
            align-items: center;
        }
        
        .field-value.empty {
            color: #a0aec0;
            font-style: italic;
        }
        
        /* Photo Section */
        .photo-section {
            width: 150px;
            height: 180px;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7fafc;
            overflow: hidden;
        }
        
        .photo-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            text-align: center;
            color: #a0aec0;
            font-size: 12px;
        }
        
        /* Footer */
        .form-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 2px solid #e2e8f0;
        }
        
        .signature-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #2d3748;
            margin-bottom: 8px;
            padding-top: 50px;
        }
        
        .signature-label {
            font-size: 12px;
            font-weight: 600;
            color: #4a5568;
        }
        
        /* Print Styles */
        @media print {            
            .a4-page {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="a4-page a4-container">
        <!-- Header with Banner and Logo -->
        <div class="form-header">
            @if(\App\Models\GeneralSetting::getValue('admission_form_banner'))
            <img src="{{ \App\Models\GeneralSetting::getValue('admission_form_banner') }}" alt="Banner" class="header-banner">
            @endif
            
            <div class="header-content">
                <div class="logo-container">
                    @if(\App\Models\GeneralSetting::getValue('admission_form_logo'))
                    <img src="{{ \App\Models\GeneralSetting::getValue('admission_form_logo') }}" alt="Logo">
                    @else
                    <div style="font-size: 40px; font-weight: bold; color: #667eea;">E</div>
                    @endif
                </div>
                
                <div class="institution-info">
                    <div class="institution-name">
                        {{ \App\Models\GeneralSetting::getValue('admission_form_institution_name', 'ERP Institution') }}
                    </div>
                    <div class="institution-details">
                        <div>📍 {{ \App\Models\GeneralSetting::getValue('admission_form_institution_address', 'Address Line 1, City, Country') }}</div>
                        <div>📞 {{ \App\Models\GeneralSetting::getValue('admission_form_phone', '+880 1234-567890') }} | 
                             ✉️ {{ \App\Models\GeneralSetting::getValue('admission_form_email', 'info@institution.edu') }} | 
                             🌐 {{ \App\Models\GeneralSetting::getValue('admission_form_website', 'www.institution.edu') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Title -->
        <div class="form-title-section">
            <div class="form-title">{{ \App\Models\GeneralSetting::getValue('admission_form_title', 'STUDENT ADMISSION FORM') }}</div>
            <div class="academic-year">Academic Year: {{ \App\Models\GeneralSetting::getValue('admission_form_academic_year', date('Y') . '-' . (date('Y') + 1)) }}</div>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <!-- Personal Information -->
            <div class="section-title">📋 Personal Information</div>
            <div class="form-grid">
                <div class="form-field">
                    <div class="field-label">Student Name</div>
                    <div class="field-value">{{ $student->name ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Date of Birth</div>
                    <div class="field-value">{{ $student->date_of_birth ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Gender</div>
                    <div class="field-value">{{ $student->gender ?? 'N/A' }}</div>
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

            <!-- Contact Information -->
            <div class="section-title">📞 Contact Information</div>
            <div class="form-grid">
                <div class="form-field">
                    <div class="field-label">Phone Number</div>
                    <div class="field-value">{{ $student->phone ?? 'N/A' }}</div>
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

            <!-- Academic Information -->
            <div class="section-title">🎓 Academic Information</div>
            <div class="form-grid">
                <div class="form-field">
                    <div class="field-label">Class</div>
                    <div class="field-value">{{ $student->class->name ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Roll Number</div>
                    <div class="field-value">{{ $student->roll_number ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Admission Date</div>
                    <div class="field-value">{{ $student->admission_date ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Session</div>
                    <div class="field-value">{{ $student->session ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div class="section-title">👨‍👩‍👧 Guardian Information</div>
            <div class="form-grid">
                <div class="form-field">
                    <div class="field-label">Father's Name</div>
                    <div class="field-value">{{ $student->father_name ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Father's Occupation</div>
                    <div class="field-value">{{ $student->father_occupation ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Mother's Name</div>
                    <div class="field-value">{{ $student->mother_name ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Mother's Occupation</div>
                    <div class="field-value">{{ $student->mother_occupation ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Guardian Phone</div>
                    <div class="field-value">{{ $student->guardian_phone ?? 'N/A' }}</div>
                </div>
                <div class="form-field">
                    <div class="field-label">Emergency Contact</div>
                    <div class="field-value">{{ $student->emergency_contact ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Signature Section -->
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
        </div>

        <!-- Footer -->
        <div class="form-footer">
            <div style="text-align: center; font-size: 11px; color: #718096;">
                <strong>Note:</strong> This is a computer-generated admission form. Please verify all information before submission.
                <br>
                Generated on: {{ date('F d, Y h:i A') }}
            </div>
        </div>
    </div>
