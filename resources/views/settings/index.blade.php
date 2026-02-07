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
                <h3 class="text-lg font-bold text-slate-900 capitalize">{{ $group }} Settings</h3>
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
