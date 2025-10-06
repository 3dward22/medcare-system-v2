<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
           <!-- <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>-->

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        {{-- ✅ Success/Error Modal for Registration/Login --}}
@if(session('success') || session('error'))
<div 
    x-data="{ show: true }" 
    x-show="show"
    x-transition.opacity.scale
    x-cloak
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-[9999]"
>
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-8 text-center relative">
        <button @click="show = false" class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

        @if(session('success'))
            <div class="text-green-500 text-5xl mb-4">✅</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Success!</h2>
            <p class="text-gray-600 text-sm">{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <div class="text-red-500 text-5xl mb-4">❌</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Error!</h2>
            <p class="text-gray-600 text-sm">{{ session('error') }}</p>
        @endif

        <button 
            @click="show = false"
            class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition"
        >
            OK
        </button>
    </div>
</div>
@endif

    </body>
</html>
