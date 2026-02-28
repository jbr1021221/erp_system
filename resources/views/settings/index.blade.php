@extends('layouts.app')

@section('title', 'System Settings - ERP System')

@section('header_title', 'System Settings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
        @csrf
        
        @foreach($settings as $group => $items)
        <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-100">
                <h3 class="text-lg font-bold text-slate-800 capitalize">{{ $group }} Settings</h3>
                <p class="mt-1 text-sm text-slate-500">Configure your {{ $group }} identifiers and preferences.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($items as $setting)
                    <div class="space-y-2">
                        <label for="{{ $setting->key }}" class="block text-sm font-semibold text-slate-700">
                            {{ $setting->display_name }}
                        </label>
                        
                        @if($setting->key == 'font_size')
                        <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-white">
                            @foreach(\App\Models\GeneralSetting::getFontSizeOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $setting->value == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Changes the font size across the entire system. Page refresh required to see changes.
                        </p>
                        @elseif(in_array($setting->key, ['admission_form_logo', 'admission_form_banner']))
                        <div class="space-y-2">
                            @if($setting->value)
                            <div class="mb-2">
                                <img src="{{ $setting->value }}" alt="{{ $setting->display_name }}" class="h-20 rounded-lg border border-slate-200">
                            </div>
                            @endif
                            <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}" placeholder="/images/{{ $setting->key == 'admission_form_logo' ? 'logo.png' : 'banner.jpg' }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-3 bg-slate-50">
                            <p class="text-xs text-slate-500">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Enter the path to the {{ $setting->key == 'admission_form_logo' ? 'logo' : 'banner' }} image (e.g., /images/{{ $setting->key == 'admission_form_logo' ? 'logo.png' : 'banner.jpg' }})
                            </p>
                        </div>
                        @elseif($setting->key == 'role_permission_style')
                        <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-3 bg-slate-50">
                            <option value="badge" {{ $setting->value == 'badge' ? 'selected' : '' }}>Badge Style</option>
                            <option value="text" {{ $setting->value == 'text' ? 'selected' : '' }}>Plain Text Style</option>
                            <option value="list" {{ $setting->value == 'list' ? 'selected' : '' }}>Bullet List Style</option>
                        </select>
                        @elseif(in_array($setting->key, ['theme_primary_color', 'theme_secondary_color']))
                        <div x-data="{
                            rgb: '{{ $setting->value ?? ($setting->key == 'theme_primary_color' ? '255 245 230' : '255 240 217') }}',
                            hex: '',
                            init() {
                                this.updateHexFromRgb();
                            },
                            updateHexFromRgb() {
                                const parts = this.rgb.split(' ');
                                if (parts.length === 3) {
                                    const [r, g, b] = parts.map(Number);
                                    this.hex = '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
                                } else {
                                    this.hex = '{{ $setting->key == 'theme_primary_color' ? '#fff5e6' : '#fff0d9' }}'; // Fallback
                                }
                            },
                            updateFromHex(e) {
                                this.hex = e.target.value;
                                const r = parseInt(this.hex.slice(1, 3), 16);
                                const g = parseInt(this.hex.slice(3, 5), 16);
                                const b = parseInt(this.hex.slice(5, 7), 16);
                                this.rgb = `${r} ${g} ${b}`;
                            },
                            updateFromPreset(hex) {
                                this.hex = hex;
                                const r = parseInt(this.hex.slice(1, 3), 16);
                                const g = parseInt(this.hex.slice(3, 5), 16);
                                const b = parseInt(this.hex.slice(5, 7), 16);
                                this.rgb = `${r} ${g} ${b}`;
                            }
                        }">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="relative shrink-0">
                                    <input type="color" x-model="hex" @input="updateFromHex" class="h-12 w-16 rounded-lg cursor-pointer border border-slate-300 p-1 bg-white shadow-sm transition-transform hover:scale-105">
                                </div>
                                <div class="flex-1">
                                    <input type="text" name="{{ $setting->key }}" x-model="rgb" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 bg-slate-50 font-mono text-slate-600" readonly>
                                </div>
                                <button type="button" 
                                    @click="updateFromPreset('{{ $setting->key == 'theme_primary_color' ? '#fff5e6' : '#fff0d9' }}')"
                                    class="inline-flex items-center px-4 py-2.5 border border-slate-300 rounded-lg shadow-sm text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 whitespace-nowrap"
                                    title="Reset to default {{ $setting->key == 'theme_primary_color' ? 'primary' : 'secondary' }} color">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </button>
                            </div>
                            
                            <!-- Color Presets -->
                            <div class="flex gap-2">
                                @if($setting->key == 'theme_primary_color')
                                    <button type="button" @click="updateFromPreset('#fff5e6')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #fff5e6;" title="Light Cream (Default)"></button>
                                    <button type="button" @click="updateFromPreset('#ffdead')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #ffdead;" title="Navajo White"></button>
                                    <button type="button" @click="updateFromPreset('#e2e8f5')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #e2e8f5;" title="Soft Blue"></button>
                                    <button type="button" @click="updateFromPreset('#ecfdf5')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #ecfdf5;" title="Green Mint"></button>
                                @else
                                    <button type="button" @click="updateFromPreset('#fff0d9')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #fff0d9;" title="Light Peach (Default)"></button>
                                    <button type="button" @click="updateFromPreset('#ffd599')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #ffd599;" title="Warm Tone"></button>
                                    <button type="button" @click="updateFromPreset('#ffffff')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #ffffff;" title="White"></button>
                                    <button type="button" @click="updateFromPreset('#f8fafc')" class="w-8 h-8 rounded-full border border-slate-300 shadow-sm transition-transform hover:scale-110 focus:outline-none ring-2 ring-transparent focus:ring-indigo-500" style="background-color: #f8fafc;" title="Soft Gray"></button>
                                @endif
                                <span class="text-xs text-slate-400 self-center ml-1">Presets</span>
                            </div>

                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd"/></svg>
                                Pick a color or select a preset.
                            </p>
                        </div>
                        @else
                        <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-3 bg-slate-50">
                        @endif
                        
                        <p class="text-xs text-slate-400 font-mono">System Key: {{ $setting->key }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-slate-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
