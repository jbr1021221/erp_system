{{-- Shared student form partial --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  {{-- Main form (2/3) --}}
  <div class="xl:col-span-2 flex flex-col gap-5">

    {{-- Personal Information --}}
    <div class="card p-6 animate-in delay-1">
      <div class="flex items-center gap-3 mb-5">
        <div style="width:4px;height:22px;background:var(--accent);border-radius:2px;flex-shrink:0;"></div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Personal Information</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-form-field name="name" label="Full Name *" type="text" :value="old('name', $student->name ?? '')" placeholder="e.g. Ahmed Abdullah" required />
        <x-form-field name="student_id" label="Student ID" type="text" :value="old('student_id', $student->student_id ?? '')" placeholder="Auto-generated if empty" />
        <x-form-field name="date_of_birth" label="Date of Birth" type="date" :value="old('date_of_birth', isset($student) ? \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') : '')" />
        <div>
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Gender</label>
          <select name="gender" style="width:100%;height:50px;padding:0 14px;border:1px solid {{ $errors->has('gender') ? 'var(--accent)' : 'var(--border-color)' }};border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
            <option value="">Select Gender</option>
            <option value="male" {{ old('gender', $student->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
          </select>
        </div>
        <x-form-field name="phone" label="Phone Number" type="tel" :value="old('phone', $student->phone ?? '')" placeholder="01XXXXXXXXX" />
        <x-form-field name="email" label="Email Address" type="email" :value="old('email', $student->email ?? '')" placeholder="student@email.com" />
        <div class="sm:col-span-2">
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Present Address</label>
          <textarea name="address" rows="2" placeholder="Full address..."
            style="width:100%;padding:12px 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;resize:vertical;font-family:'DM Sans',sans-serif;"
            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ old('address', $student->address ?? '') }}</textarea>
        </div>
      </div>
    </div>

    {{-- Academic Information --}}
    <div class="card p-6 animate-in delay-2">
      <div class="flex items-center gap-3 mb-5">
        <div style="width:4px;height:22px;background:var(--accent-green);border-radius:2px;flex-shrink:0;"></div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Academic Information</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Class *</label>
          <select name="class_id" required style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
            <option value="">Select Class</option>
            @foreach($classes ?? [] as $class)
              <option value="{{ $class->id }}" {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
            @endforeach
          </select>
          @error('class_id')<p style="font-size:11px;color:var(--accent);margin-top:4px;">{{ $message }}</p>@enderror
        </div>
        <x-form-field name="section" label="Section" type="text" :value="old('section', $student->section ?? '')" placeholder="e.g. A" />
        <x-form-field name="roll_number" label="Roll Number" type="number" :value="old('roll_number', $student->roll_number ?? '')" placeholder="e.g. 12" />
        <x-form-field name="admission_date" label="Admission Date *" type="date" :value="old('admission_date', isset($student) ? \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') : date('Y-m-d'))" required />
        <div>
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Status</label>
          <select name="status" style="width:100%;height:50px;padding:0 14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
            <option value="active" {{ old('status', $student->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $student->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="transferred" {{ old('status', $student->status ?? '') == 'transferred' ? 'selected' : '' }}>Transferred</option>
          </select>
        </div>
      </div>
    </div>

    {{-- Guardian Information --}}
    <div class="card p-6 animate-in delay-3">
      <div class="flex items-center gap-3 mb-5">
        <div style="width:4px;height:22px;background:var(--accent-gold);border-radius:2px;flex-shrink:0;"></div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Guardian Information</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-form-field name="guardian_name" label="Guardian Name *" type="text" :value="old('guardian_name', $student->guardian_name ?? '')" placeholder="Father / Mother name" required />
        <x-form-field name="guardian_phone" label="Guardian Phone *" type="tel" :value="old('guardian_phone', $student->guardian_phone ?? '')" placeholder="01XXXXXXXXX" required />
        <x-form-field name="guardian_relation" label="Relation" type="text" :value="old('guardian_relation', $student->guardian_relation ?? '')" placeholder="Father / Mother / Uncle..." />
        <x-form-field name="guardian_email" label="Guardian Email" type="email" :value="old('guardian_email', $student->guardian_email ?? '')" placeholder="guardian@email.com" />
      </div>
    </div>

  </div>

  {{-- Sidebar (1/3) --}}
  <div class="flex flex-col gap-5">

    {{-- Photo Upload --}}
    <div class="card p-6 animate-in delay-1">
      <div class="flex items-center gap-3 mb-4">
        <div style="width:4px;height:22px;background:var(--accent-info);border-radius:2px;"></div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);">Student Photo</h2>
      </div>
      <div class="flex flex-col items-center gap-3">
        <div id="photoPreview"
             style="width:100px;height:100px;border-radius:16px;background:var(--bg-surface-2);border:2px dashed var(--border-color);display:flex;align-items:center;justify-content:center;overflow:hidden;cursor:pointer;"
             onclick="document.getElementById('photoInput').click()">
          @if(isset($student) && $student->photo)
            <img src="{{ asset('storage/'.$student->photo) }}" style="width:100%;height:100%;object-fit:cover;">
          @else
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--text-muted);"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          @endif
        </div>
        <input type="file" id="photoInput" name="photo" accept="image/*" class="hidden"
               onchange="const r=new FileReader();r.onload=e=>{document.getElementById('photoPreview').innerHTML='<img src='+e.target.result+' style=width:100%;height:100%;object-fit:cover>';};r.readAsDataURL(this.files[0])">
        <button type="button" onclick="document.getElementById('photoInput').click()"
                style="font-size:12px;color:var(--accent);font-weight:600;background:none;border:none;cursor:pointer;">Upload Photo</button>
        <p style="font-size:11px;color:var(--text-muted);text-align:center;">JPG, PNG up to 2MB</p>
      </div>
    </div>

    {{-- Quick Notes --}}
    <div class="card p-6 animate-in delay-2">
      <div class="flex items-center gap-3 mb-4">
        <div style="width:4px;height:22px;background:var(--accent-gold);border-radius:2px;"></div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);">Notes</h2>
      </div>
      <textarea name="notes" rows="4" placeholder="Any special notes about this student..."
        style="width:100%;padding:12px;border:1px solid var(--border-color);border-radius:12px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;resize:none;font-family:'DM Sans',sans-serif;"
        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ old('notes', $student->notes ?? '') }}</textarea>
    </div>

    {{-- Form Actions --}}
    <div class="card p-5 animate-in delay-3">
      <button type="submit"
        style="width:100%;height:48px;background:var(--accent);color:white;border:none;border-radius:12px;font-family:'Syne',sans-serif;font-weight:700;font-size:15px;cursor:pointer;transition:all 0.2s;margin-bottom:10px;"
        onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent)'">
        {{ isset($student) ? 'Update Student' : 'Create Student' }}
      </button>
      <a href="{{ route('students.index') }}"
         style="display:block;text-align:center;height:44px;line-height:44px;background:var(--bg-surface-2);border-radius:12px;font-size:14px;font-weight:500;color:var(--text-secondary);text-decoration:none;transition:all 0.2s;"
         onmouseover="this.style.background='var(--border-color)'" onmouseout="this.style.background='var(--bg-surface-2)'">
        Cancel
      </a>
    </div>

  </div>
</div>
