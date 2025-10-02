<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-4">Enter OTP</h2>

            @if(session('success'))
                <div class="text-green-600 mb-2">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="text-red-600 mb-2">{{ session('error') }}</div>
            @endif

            <form action="{{ route('otp.verify.post') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') ?? '' }}">

                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700">OTP</label>
                    <input id="otp" name="otp" type="text" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('otp')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Verify OTP
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
