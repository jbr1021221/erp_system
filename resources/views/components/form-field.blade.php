@props(['name', 'label', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false])
<div>
  <label for="{{ $name }}" style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">
    {{ $label }}
  </label>
  <input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    style="width:100%;height:50px;padding:0 14px;border:1.5px solid {{ $errors->has($name) ? 'var(--accent)' : 'var(--border-color)' }};border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;transition:border-color 0.2s,box-shadow 0.2s;font-family:'DM Sans',sans-serif;"
    onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px rgba(212,80,30,0.1)'"
    onblur="this.style.borderColor='{{ $errors->has($name) ? 'var(--accent)' : 'var(--border-color)' }}';this.style.boxShadow='none'"
  >
  @error($name)
    <p style="font-size:11px;color:var(--accent);margin-top:4px;display:flex;align-items:center;gap:4px;">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ $message }}
    </p>
  @enderror
</div>
