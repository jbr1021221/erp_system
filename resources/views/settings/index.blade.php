@extends('layouts.app')

@section('page-title', 'Settings')
@section('title', 'System Settings - ERP System')
@section('header_title', 'System Settings')

@section('content')
<div style="max-width:1100px;margin:0 auto;">

  <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
    @csrf

    @foreach($settings as $group => $items)
    <div style="background:var(--bg-surface);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">

      {{-- Group Header --}}
      <div style="padding:18px 24px;border-bottom:1px solid var(--border-color);background:var(--bg-surface-2);">
        <h3 style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--text-primary);text-transform:capitalize;margin:0;">
          {{ $group }} Settings
        </h3>
        <p style="font-size:13px;color:var(--text-muted);margin:4px 0 0;">
          Configure your {{ $group }} identifiers and preferences.
        </p>
      </div>

      {{-- Group Body --}}
      <div style="padding:24px;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          @foreach($items as $setting)
          <div>
            <label for="{{ $setting->key }}"
                   style="display:block;font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.6px;">
              {{ $setting->display_name }}
            </label>

            {{-- ── Font Size select ── --}}
            @if($setting->key == 'font_size')
              <select name="{{ $setting->key }}" id="{{ $setting->key }}"
                      style="width:100%;height:42px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;transition:border-color 0.2s;"
                      onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                @foreach(\App\Models\GeneralSetting::getFontSizeOptions() as $value => $label)
                  <option value="{{ $value }}" {{ $setting->value == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              <p style="font-size:11px;color:var(--text-muted);margin-top:6px;display:flex;align-items:center;gap:4px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Changes font size across the system. Page refresh required.
              </p>

            {{-- ── Image path inputs ── --}}
            @elseif(in_array($setting->key, ['admission_form_logo', 'admission_form_banner']))
              @if($setting->value)
                <div style="margin-bottom:8px;">
                  <img src="{{ $setting->value }}" alt="{{ $setting->display_name }}"
                       style="height:64px;border-radius:8px;border:1px solid var(--border-color);">
                </div>
              @endif
              <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}"
                     placeholder="/images/{{ $setting->key == 'admission_form_logo' ? 'logo.png' : 'banner.jpg' }}"
                     style="width:100%;height:42px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                     onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
              <p style="font-size:11px;color:var(--text-muted);margin-top:6px;">
                Enter the path to the {{ $setting->key == 'admission_form_logo' ? 'logo' : 'banner' }} image.
              </p>

            {{-- ── Role permission style select ── --}}
            @elseif($setting->key == 'role_permission_style')
              <select name="{{ $setting->key }}" id="{{ $setting->key }}"
                      style="width:100%;height:42px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;cursor:pointer;transition:border-color 0.2s;"
                      onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                <option value="badge"  {{ $setting->value == 'badge'  ? 'selected' : '' }}>Badge Style</option>
                <option value="text"   {{ $setting->value == 'text'   ? 'selected' : '' }}>Plain Text Style</option>
                <option value="list"   {{ $setting->value == 'list'   ? 'selected' : '' }}>Bullet List Style</option>
              </select>

            {{-- ── Color pickers ── --}}
            @elseif(in_array($setting->key, ['theme_primary_color', 'theme_secondary_color']))
            @php
              $defaultHex = $setting->key == 'theme_primary_color' ? '#fff5e6' : '#fff0d9';
              $presets = $setting->key == 'theme_primary_color'
                ? [['#fff5e6','Light Cream'],['#ffdead','Navajo White'],['#e2e8f5','Soft Blue'],['#ecfdf5','Green Mint']]
                : [['#fff0d9','Light Peach'],['#ffd599','Warm Tone'],['#ffffff','White'],['#f8fafc','Soft Gray']];
            @endphp
            <div x-data="{
              rgb: '{{ $setting->value ?? ($setting->key == 'theme_primary_color' ? '255 245 230' : '255 240 217') }}',
              hex: '',
              init() { this.updateHexFromRgb(); },
              updateHexFromRgb() {
                const parts = this.rgb.split(' ');
                if (parts.length === 3) {
                  const [r,g,b] = parts.map(Number);
                  this.hex = '#' + [r,g,b].map(x => x.toString(16).padStart(2,'0')).join('');
                } else { this.hex = '{{ $defaultHex }}'; }
              },
              updateFromHex(e) {
                this.hex = e.target.value;
                const r = parseInt(this.hex.slice(1,3),16);
                const g = parseInt(this.hex.slice(3,5),16);
                const b = parseInt(this.hex.slice(5,7),16);
                this.rgb = `${r} ${g} ${b}`;
              },
              updateFromPreset(hex) {
                this.hex = hex;
                const r = parseInt(hex.slice(1,3),16);
                const g = parseInt(hex.slice(3,5),16);
                const b = parseInt(hex.slice(5,7),16);
                this.rgb = `${r} ${g} ${b}`;
              }
            }">
              <div class="flex items-center gap-3" style="margin-bottom:10px;">
                <input type="color" x-model="hex" @input="updateFromHex"
                       style="width:48px;height:42px;border-radius:8px;cursor:pointer;border:1px solid var(--border-color);background:var(--bg-base);padding:3px;">
                <input type="text" name="{{ $setting->key }}" x-model="rgb" readonly
                       style="flex:1;height:42px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;font-family:monospace;background:var(--bg-base);color:var(--text-primary);outline:none;">
                <button type="button" @click="updateFromPreset('{{ $defaultHex }}')"
                        style="height:42px;padding:0 14px;border:1px solid var(--border-color);border-radius:10px;font-size:12px;font-weight:600;color:var(--text-secondary);background:var(--bg-surface);cursor:pointer;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;transition:background 0.2s;"
                        onmouseover="this.style.background='var(--bg-surface-2)'" onmouseout="this.style.background='var(--bg-surface)'">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  Reset
                </button>
              </div>
              <div style="display:flex;align-items:center;gap:8px;">
                @foreach($presets as [$hex, $title])
                  <button type="button" @click="updateFromPreset('{{ $hex }}')"
                          title="{{ $title }}"
                          style="width:28px;height:28px;border-radius:50%;border:2px solid var(--border-color);cursor:pointer;background:{{ $hex }};transition:transform 0.15s;flex-shrink:0;"
                          onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'"></button>
                @endforeach
                <span style="font-size:11px;color:var(--text-muted);margin-left:4px;">Presets</span>
              </div>
              <p style="font-size:11px;color:var(--text-muted);margin-top:8px;">Pick a color or select a preset.</p>
            </div>

            {{-- ── Default text input ── --}}
            @else
              <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}"
                     style="width:100%;height:42px;padding:0 12px;border:1px solid var(--border-color);border-radius:10px;font-size:13px;background:var(--bg-base);color:var(--text-primary);outline:none;transition:border-color 0.2s;box-sizing:border-box;"
                     onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
            @endif

            <p style="font-size:10px;color:var(--text-muted);font-family:monospace;margin-top:5px;opacity:0.6;">
              {{ $setting->key }}
            </p>
          </div>
          @endforeach

        </div>
      </div>
    </div>
    @endforeach

    {{-- Save Button --}}
    <div style="display:flex;justify-content:flex-end;padding-top:8px;">
      <button type="submit"
              style="display:inline-flex;align-items:center;gap:8px;padding:11px 28px;background:linear-gradient(135deg,var(--accent),var(--accent-hi));color:white;border:none;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 15px rgba(5,150,105,0.3);transition:all 0.2s;"
              onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(5,150,105,0.4)'"
              onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(5,150,105,0.3)'">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        Save All Settings
      </button>
    </div>

  </form>
</div>
@endsection