<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-cyan-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">MedCare Portal</h2>
                <p class="text-sm text-gray-600">Access your healthcare dashboard</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-semibold" />
                    <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Doctor@medcare.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                    <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required placeholder="Enter your password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 h-4 w-4" name="remember">
                        <span class="ml-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
                    </label>
                </div>
                <div class="mt-6">
                    <x-primary-button class="w-full">
                        {{ __('Sign In to Portal') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-gray-500">
                <p>Protected by HIPAA compliance standards</p>
                <p class="mt-1">© 2025 MedCare Health Systems. All rights reserved.</p>
            </div>
        </div>

        <!-- OTP Modal (AlpineJS) -->
        <!-- OTP Modal (AlpineJS with animation) -->
@if(session('show_otp_modal'))
    <div x-data="{ open: true }"
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="scale-90 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="scale-100 opacity-100"
             x-transition:leave-end="scale-90 opacity-0"
             class="bg-white rounded-2xl shadow-2xl w-96 p-8 relative">

            <!-- Close Button -->
            <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>

            <!-- Header -->
            <h2 class="text-xl font-bold mb-4 text-center">Enter OTP</h2>
            <p class="text-sm mb-4 text-center">An OTP has been sent to your email.</p>

            <!-- OTP Form -->
            <form method="POST" action="{{ route('otp.verify.post') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ session('otp_user_email') ?? '' }}">
                <div>
                    <x-text-input id="otp" name="otp" type="text" placeholder="Enter OTP" required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg"
                        x-ref="otpInput"
                        x-init="$nextTick(() => $refs.otpInput.focus())" />
                    <x-input-error :messages="$errors->get('otp')" class="mt-1" />
                </div>
                <button type="submit" class="w-full py-2 px-4 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition duration-150">Verify OTP</button>
            </form>
        </div>
    </div>
@endif

    </div>
</x-guest-layout>
