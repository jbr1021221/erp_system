{{-- ═══════════════════════════════════════════════════════
     Shared student form partial (create & edit)
     Requires: $classes, $sections from controller
     ═══════════════════════════════════════════════════════ --}}

{{-- Inline style helper shared across inputs --}}
@php
  $inputStyle   = "width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;transition:border-color 0.15s;";
  $focusAttrs   = "onfocus=\"this.style.borderColor='var(--accent)'\" onblur=\"this.style.borderColor='var(--border-color)'\"";
  $labelStyle   = "font-size:12px;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:6px;letter-spacing:0.02em;text-transform:uppercase;";
  $preselectedSection = old('section_id', $student->section_id ?? null);
@endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  {{-- ── Left: Main form (2/3) ─────────────────────────────── --}}
  <div class="xl:col-span-2 flex flex-col gap-5">

    {{-- ── SECTION 1: Personal Information ──────────────────── --}}
    <div class="card p-6 animate-in delay-1" style="border-top:3px solid var(--accent);">
      <div class="flex items-center gap-3 mb-5">
        <div style="width:32px;height:32px;border-radius:8px;background:rgba(5,150,105,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
          <h2 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);">Personal Information</h2>
          <p style="font-size:11px;color:var(--text-muted);margin-top:1px;">Student's basic identity details</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- First Name --}}
        <div>
          <label style="{{ $labelStyle }}">First Name <span style="color:var(--accent);">*</span></label>
          <input type="text" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}"
            placeholder="e.g. Ahmed" required
            style="{{ $inputStyle }}{{ $errors->has('first_name') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
          @error('first_name')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Last Name --}}
        <div>
          <label style="{{ $labelStyle }}">Last Name <span style="color:var(--accent);">*</span></label>
          <input type="text" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}"
            placeholder="e.g. Abdullah" required
            style="{{ $inputStyle }}{{ $errors->has('last_name') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
          @error('last_name')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Date of Birth --}}
        <div>
          <label style="{{ $labelStyle }}">Date of Birth</label>
          <input type="date" name="date_of_birth"
            value="{{ old('date_of_birth', isset($student) ? \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') : '') }}"
            style="{{ $inputStyle }}" {!! $focusAttrs !!}>
        </div>

        {{-- Gender --}}
        <div>
          <label style="{{ $labelStyle }}">Gender</label>
          <select name="gender" style="{{ $inputStyle }}cursor:pointer;" {!! $focusAttrs !!}>
            <option value="">Select Gender</option>
            <option value="male"  {{ old('gender', $student->gender ?? '') == 'male'   ? 'selected' : '' }}>Male</option>
            <option value="female"{{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender', $student->gender ?? '') == 'other'  ? 'selected' : '' }}>Other</option>
          </select>
        </div>

        {{-- Phone --}}
        <div>
          <label style="{{ $labelStyle }}">Phone Number</label>
          <input type="tel" name="phone" value="{{ old('phone', $student->phone ?? '') }}"
            placeholder="01XXXXXXXXX" style="{{ $inputStyle }}" {!! $focusAttrs !!}>
        </div>

        {{-- Email --}}
        <div>
          <label style="{{ $labelStyle }}">Email Address</label>
          <input type="email" name="email" value="{{ old('email', $student->email ?? '') }}"
            placeholder="student@email.com"
            style="{{ $inputStyle }}{{ $errors->has('email') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
          @error('email')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Address --}}
        <div class="sm:col-span-2">
          <label style="{{ $labelStyle }}">Present Address</label>
          <textarea name="address" rows="2" placeholder="Full address..."
            style="width:100%;padding:13px 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;resize:vertical;transition:border-color 0.15s;"
            {!! $focusAttrs !!}>{{ old('address', $student->address ?? '') }}</textarea>
        </div>

      </div>
    </div>

    {{-- ── SECTION 2: Academic Information ───────────────────── --}}
    <div class="card p-6 animate-in delay-2" style="border-top:3px solid var(--accent-info);">
      <div class="flex items-center gap-3 mb-5">
        <div style="width:32px;height:32px;border-radius:8px;background:rgba(37,99,235,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-info)" stroke-width="2.2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        </div>
        <div>
          <h2 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);">Academic Information</h2>
          <p style="font-size:11px;color:var(--text-muted);margin-top:1px;">Class, section and enrollment details</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Student ID --}}
        <div>
          <label style="{{ $labelStyle }}">Student ID</label>
          <input type="text" name="student_id" value="{{ old('student_id', $student->student_id ?? '') }}"
            placeholder="Auto-generated if empty"
            style="{{ $inputStyle }}font-family:'Outfit',sans-serif;letter-spacing:0.05em;" {!! $focusAttrs !!}>
          <p style="font-size:11px;color:var(--text-muted);margin-top:4px;">Leave blank to auto-generate from class and ID.</p>
        </div>

        {{-- Enrollment Date --}}
        <div>
          <label style="{{ $labelStyle }}">Enrollment Date <span style="color:var(--accent);">*</span></label>
          <input type="date" name="enrollment_date" required
            value="{{ old('enrollment_date', isset($student) ? \Carbon\Carbon::parse($student->enrollment_date)->format('Y-m-d') : date('Y-m-d')) }}"
            style="{{ $inputStyle }}{{ $errors->has('enrollment_date') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
          @error('enrollment_date')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Class --}}
        <div>
          <label style="{{ $labelStyle }}">Class <span style="color:var(--accent);">*</span></label>
          <select id="studentClass" name="class_id" required
            style="{{ $inputStyle }}cursor:pointer;{{ $errors->has('class_id') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
            <option value="">Select Class</option>
            @foreach($classes ?? [] as $class)
              <option value="{{ $class->id }}" {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
          </select>
          @error('class_id')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Section (filtered by class via JS) --}}
        <div>
          <label style="{{ $labelStyle }}">Section</label>
          <select id="studentSection" name="section_id" disabled
            style="{{ $inputStyle }}cursor:pointer;opacity:0.5;" {!! $focusAttrs !!}>
            <option value="">— Select Class first —</option>
          </select>
        </div>

        {{-- Status --}}
        <div>
          <label style="{{ $labelStyle }}">Status <span style="color:var(--accent);">*</span></label>
          <select name="status" required style="{{ $inputStyle }}cursor:pointer;" {!! $focusAttrs !!}>
            <option value="active"     {{ old('status', $student->status ?? 'active') == 'active'      ? 'selected' : '' }}>Active</option>
            <option value="inactive"   {{ old('status', $student->status ?? '')       == 'inactive'    ? 'selected' : '' }}>Inactive</option>
            <option value="transferred"{{ old('status', $student->status ?? '')       == 'transferred' ? 'selected' : '' }}>Transferred</option>
          </select>
        </div>

      </div>
    </div>

    {{-- ── SECTION 3: Guardian Information ────────────────────── --}}
    <div class="card p-6 animate-in delay-3" style="border-top:3px solid var(--accent-gold);">
      <div class="flex items-center gap-3 mb-5">
        <div style="width:32px;height:32px;border-radius:8px;background:rgba(217,119,6,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-gold)" stroke-width="2.2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div>
          <h2 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);">Guardian Information</h2>
          <p style="font-size:11px;color:var(--text-muted);margin-top:1px;">Parent or guardian contact details</p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div>
          <label style="{{ $labelStyle }}">Guardian Name <span style="color:var(--accent);">*</span></label>
          <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name ?? '') }}"
            placeholder="Father / Mother name" required
            style="{{ $inputStyle }}{{ $errors->has('guardian_name') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
          @error('guardian_name')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div>
          <label style="{{ $labelStyle }}">Guardian Phone <span style="color:var(--accent);">*</span></label>
          <input type="tel" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}"
            placeholder="01XXXXXXXXX" required
            style="{{ $inputStyle }}{{ $errors->has('guardian_phone') ? 'border-color:var(--accent-danger);' : '' }}"
            {!! $focusAttrs !!}>
          @error('guardian_phone')<p style="font-size:11px;color:var(--accent-danger);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div>
          <label style="{{ $labelStyle }}">Relation</label>
          <input type="text" name="guardian_relation" value="{{ old('guardian_relation', $student->guardian_relation ?? '') }}"
            placeholder="Father / Mother / Uncle..." style="{{ $inputStyle }}" {!! $focusAttrs !!}>
        </div>

        <div>
          <label style="{{ $labelStyle }}">Guardian Email</label>
          <input type="email" name="guardian_email" value="{{ old('guardian_email', $student->guardian_email ?? '') }}"
            placeholder="guardian@email.com" style="{{ $inputStyle }}" {!! $focusAttrs !!}>
        </div>

      </div>
    </div>

  </div>

  {{-- ── Right: Sidebar (1/3) ─────────────────────────────── --}}
  <div class="flex flex-col gap-5">

    {{-- Photo Upload --}}
    <div class="card p-6 animate-in delay-1">
      <div class="flex items-center gap-3 mb-4">
        <div style="width:4px;height:20px;background:var(--accent-info);border-radius:2px;"></div>
        <h2 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:14px;color:var(--text-primary);">Student Photo</h2>
      </div>
      <div class="flex flex-col items-center gap-3">
        <div id="photoPreview"
             onclick="document.getElementById('photoInput').click()"
             style="width:110px;height:110px;border-radius:16px;background:var(--bg-surface-2);border:2px dashed var(--border-color);display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;cursor:pointer;transition:border-color 0.2s;"
             onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border-color)'">
          @if(isset($student) && $student->photo)
            <img src="{{ asset('storage/'.$student->photo) }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--text-muted);"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <p style="font-size:10px;color:var(--text-muted);margin-top:6px;text-align:center;padding:0 8px;">Click to upload</p>
          @endif
        </div>
        <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden"
               onchange="const r=new FileReader();r.onload=e=>{document.getElementById('photoPreview').innerHTML='<img src='+e.target.result+' style=width:100%;height:100%;object-fit:cover>';};r.readAsDataURL(this.files[0])">
        <button type="button" onclick="document.getElementById('photoInput').click()"
                style="font-size:12px;color:var(--accent);font-weight:600;background:none;border:none;cursor:pointer;text-decoration:underline;text-underline-offset:2px;">
          Upload Photo
        </button>
        <p style="font-size:11px;color:var(--text-muted);text-align:center;">JPG, PNG up to 2MB</p>
      </div>
    </div>

    {{-- Notes --}}
    <div class="card p-6 animate-in delay-2">
      <div class="flex items-center gap-3 mb-4">
        <div style="width:4px;height:20px;background:var(--accent-gold);border-radius:2px;"></div>
        <h2 style="font-family:'Outfit',sans-serif;font-weight:700;font-size:14px;color:var(--text-primary);">Notes</h2>
      </div>
      <textarea name="notes" rows="4" placeholder="Any special notes about this student..."
        style="width:100%;padding:12px;border:1px solid var(--border-color);border-radius:12px;font-size:13px;background:var(--bg-surface);color:var(--text-primary);outline:none;resize:none;transition:border-color 0.15s;"
        {!! $focusAttrs !!}>{{ old('notes', $student->notes ?? '') }}</textarea>
    </div>

    {{-- Required fields note --}}
    <div style="padding:12px 14px;background:rgba(5,150,105,0.06);border:1px solid rgba(5,150,105,0.2);border-radius:12px;" class="animate-in delay-3">
      <p style="font-size:11px;color:var(--text-muted);line-height:1.6;">
        <span style="color:var(--accent);font-weight:700;">*</span> Required fields.<br>
        Student ID is auto-generated if left blank.<br>
        Section options update based on the selected class.
      </p>
    </div>

    {{-- Form Actions --}}
    <div class="card p-5 animate-in delay-4" style="border-top:3px solid var(--accent-green);">
      <button type="submit" form="studentForm"
        style="width:100%;height:50px;background:var(--accent);color:#fff;border:none;border-radius:12px;font-family:'Outfit',sans-serif;font-weight:700;font-size:15px;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:12px;"
        onmouseover="this.style.background='var(--accent-hover)';this.style.transform='translateY(-1px)'"
        onmouseout="this.style.background='var(--accent)';this.style.transform='translateY(0)'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ isset($student) ? 'Update Student' : 'Create Student' }}
      </button>
      <a href="{{ route('students.index') }}"
         style="display:flex;align-items:center;justify-content:center;height:44px;border-radius:12px;font-size:13px;font-weight:600;color:var(--text-secondary);text-decoration:none;background:var(--bg-surface-2);border:1px solid var(--border-color);transition:all 0.2s;"
         onmouseover="this.style.background='var(--border-color)'" onmouseout="this.style.background='var(--bg-surface-2)'">
        Cancel
      </a>
    </div>

  </div>
</div>

@push('scripts')
<script>
(function () {
    const allSections   = {!! json_encode($sections->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'class_id' => $s->class_id])->values()->toArray()) !!};
    const preselectedId = {{ old('section_id', $student->section_id ?? 'null') }};

    const $classEl   = document.getElementById('studentClass');
    const $sectionEl = document.getElementById('studentSection');

    function populateSections(classId, selectedId) {
        const filtered = allSections.filter(function(s) { return s.class_id == classId; });
        $sectionEl.innerHTML = '<option value="">Select Section</option>';

        if (!classId || filtered.length === 0) {
            $sectionEl.disabled = true;
            $sectionEl.style.opacity = '0.5';
            $sectionEl.style.cursor = 'not-allowed';
            return;
        }

        filtered.forEach(function(s) {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            if (selectedId && s.id == selectedId) opt.selected = true;
            $sectionEl.appendChild(opt);
        });

        $sectionEl.disabled = false;
        $sectionEl.style.opacity = '1';
        $sectionEl.style.cursor = 'pointer';
    }

    // On page load (edit mode or failed validation)
    if ($classEl.value) {
        populateSections($classEl.value, preselectedId);
    }

    // On class change
    $classEl.addEventListener('change', function () {
        populateSections(this.value, null);
    });
})();
</script>
@endpush
