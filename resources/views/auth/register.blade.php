<x-guest-layout>
    <div 
        x-data="{ 
            role: '{{ old('role') }}', 
            showAdminModal: {{ session('show_admin_modal') ? 'true' : 'false' }} 
        }"
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-cyan-50 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl">

            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-sm text-gray-600">Join the MedCare healthcare system</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Full Name -->
                <div>
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                        :value="old('name')" required autofocus placeholder="John Doe" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required placeholder="your.email@medcare.com" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="new-password"
                        placeholder="Create a strong password" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required
                        placeholder="Re-enter your password" />
                </div>

                <!-- Role Selection -->
                <div>
                    <x-input-label for="role" :value="__('Select Role')" />
                    <select id="role" name="role" x-model="role"
                            class="block mt-1 w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" required>
                        <option value="" disabled selected>Choose your role</option>
                        <option value="admin">👨‍💼 Administrator</option>
                        <option value="nurse">👩‍⚕️ Nurse</option>
                        <option value="student">🎓 Student</option>
                    </select>
                </div>

                <!-- Admin Code Trigger -->
                <div x-show="role === 'admin'" class="mt-4">
                    <button type="button"
                        @click="showAdminModal = true"
                        class="w-full py-2 px-4 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                        Enter Admin Access Code
                    </button>
                </div>

                <!-- Student Fields -->
                <div x-show="role === 'student'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="space-y-4">

                    <div>
                        <x-input-label for="student_phone" :value="__('Student Phone Number')" />
                        <x-text-input id="student_phone" class="block mt-1 w-full"
                            type="text" name="student_phone" placeholder="09XXXXXXXXX" />
                    </div>

                    <div>
                        <x-input-label for="guardian_name" :value="__('Guardian’s Full Name')" />
                        <x-text-input id="guardian_name" class="block mt-1 w-full"
                            type="text" name="guardian_name" placeholder="Parent or Guardian’s name" />
                    </div>

                    <div>
                        <x-input-label for="guardian_phone" :value="__('Guardian’s Phone Number')" />
                        <x-text-input id="guardian_phone" class="block mt-1 w-full"
                            type="text" name="guardian_phone" placeholder="09XXXXXXXXX" />
                    </div>
                </div>

                <!-- Register Button -->
                <div class="mt-6">
                    <x-primary-button class="w-full py-3">
                        {{ __('Create Account') }}
                    </x-primary-button>
                </div>

                <!-- Already have account -->
                <div class="mt-6 text-center text-sm">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Already have an account? Sign in
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-xs text-gray-500">
                <p>Protected by HIPAA compliance standards</p>
                <p class="mt-1">© 2025 MedCare Health Systems. All rights reserved.</p>
            </div>
        </div>

        <!-- Admin Access Code Modal -->
        <div 
            x-show="showAdminModal || {{ session('show_admin_modal') ? 'true' : 'false' }}"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
            x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl w-96 p-8 relative"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0">

                <!-- Close Button -->
                <button @click="showAdminModal = false" 
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>

                <!-- Modal Header -->
                <h2 class="text-xl font-bold mb-4 text-center text-gray-800">🔒 Admin Access Code</h2>
                <p class="text-sm text-gray-600 text-center mb-4">Please enter the secret code provided by the system administrator.</p>

                <!-- Input Field -->
                <div>
                    <x-input-label for="admin_code" :value="__('Access Code')" />
                    <x-text-input id="admin_code" 
                        class="block mt-1 w-full"
                        type="password" 
                        name="admin_code" 
                        placeholder="Enter secret code" />
                </div>

                <!-- Confirm Button -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Confirm Code
                    </button>

                    @error('admin_code')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Centered success/error dialog -->
    @if(session('success') || session('error'))
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-80 text-center">
            @if(session('success'))
                <div class="text-green-600 text-3xl mb-3">✅</div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Success!</h3>
                <p class="text-sm text-gray-600">{{ session('success') }}</p>
            @endif

            @if(session('error'))
                <div class="text-red-600 text-3xl mb-3">❌</div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Error</h3>
                <p class="text-sm text-gray-600">{{ session('error') }}</p>
            @endif

            <button 
                @click="show = false" 
                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg"
            >
                OK
            </button>
        </div>
    </div>
    @endif
</x-guest-layout>
