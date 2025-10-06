<x-guest-layout>
    <div 
        x-data="{ show: false }" 
        x-init="setTimeout(() => show = true, 200)" 
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-cyan-100 py-12 px-4 sm:px-6 lg:px-8 overflow-hidden"
    >
        <div 
            x-show="show" 
            x-transition:enter="transition ease-out duration-700" 
            x-transition:enter-start="opacity-0 translate-y-10 scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="max-w-md w-full space-y-8 bg-white/90 backdrop-blur-sm p-10 rounded-2xl shadow-2xl border border-blue-100"
        >
            <!-- Logo and Header -->
            <div class="text-center animate-fade-in">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-full flex items-center justify-center mb-4 shadow-lg animate-bounce-slow">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-1">Welcome to MedCare</h2>
                <p class="text-sm text-gray-500">Your trusted healthcare portal</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
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
                    <x-text-input id="email" class="mt-1 block w-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400" type="email" name="email" :value="old('email')" required autofocus placeholder="your@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                    <x-text-input id="password" class="mt-1 block w-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400" type="password" name="password" required placeholder="Enter your password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4 w-4" name="remember">
                        <span class="ml-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="mt-6 space-y-3">
                    <x-primary-button class="w-full transition transform hover:scale-[1.02] shadow-md hover:shadow-lg bg-gradient-to-r from-blue-600 to-cyan-500">
                        {{ __('Sign In to Portal') }}
                    </x-primary-button>

                    <!-- Registration Button -->
                    <a href="{{ route('register') }}" class="block w-full text-center py-2 border border-blue-400 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition duration-200">
                        {{ __('Create New Account') }}
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-gray-500">
                <p>Protected by HIPAA compliance standards</p>
                <p class="mt-1">© 2025 MedCare Health Systems. All rights reserved.</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .animate-bounce-slow { animation: bounce-slow 3s infinite ease-in-out; }
    </style>
</x-guest-layout>
