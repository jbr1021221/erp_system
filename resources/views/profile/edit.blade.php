@extends('layouts.app')

@section('title', 'Profile - ERP System')

@section('header_title', 'Profile Settings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">
    
    <!-- Profile Information -->
    <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Profile Information</h3>
            <p class="mt-1 text-sm text-slate-500">Update your account's profile information and email address.</p>
        </div>
        <div class="p-6">
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6 max-w-xl">
                @csrf
                @method('patch')

                <!-- Photo -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Photo</label>
                    <div class="mt-2 flex items-center space-x-6">
                        <div class="shrink-0">
                            @if(Auth::user()->photo)
                                <img class="h-14 w-16 object-cover rounded-full ring-2 ring-slate-100" src="{{ Storage::url(Auth::user()->photo) }}" alt="Current profile photo" />
                            @else
                                <div class="h-14 w-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-800 font-bold text-2xl ring-2 ring-slate-50">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <label class="block">
                            <span class="sr-only">Choose profile photo</span>
                            <input type="file" name="photo" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-slate-100 file:text-slate-800
                                hover:file:bg-slate-200
                                transition-colors cursor-pointer
                            "/>
                        </label>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2.5">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2.5">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-slate-800">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-sm text-slate-800 hover:text-slate-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-6">
                    <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent bg-slate-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors">
                        Save Changes
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Saved successfully.
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Update Password</h3>
            <p class="mt-1 text-sm text-slate-500">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <div class="p-6">
            <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700">Current Password</label>
                    <input type="password" name="current_password" id="current_password" autocomplete="current-password" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2.5">
                    @error('current_password', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">New Password</label>
                    <input type="password" name="password" id="password" autocomplete="new-password" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2.5">
                    @error('password', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2.5">
                    @error('password_confirmation', 'updatePassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-6">
                    <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent bg-slate-900 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors">
                        Update Password
                    </button>

                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Password updated.
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-red-50 rounded-xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-red-100 bg-red-50">
            <h3 class="text-lg font-bold text-red-900">Delete Account</h3>
            <p class="mt-1 text-sm text-red-600">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        </div>
        <div class="p-6">
            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            >{{ __('Delete Account') }}</x-danger-button>

            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                    @csrf
                    @method('delete')

                    <h2 class="text-lg font-medium text-slate-800">
                        {{ __('Are you sure you want to delete your account?') }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-600">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="{{ __('Password') }}"
                        />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ml-3">
                            {{ __('Delete Account') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</div>
@endsection
