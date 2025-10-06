<!DOCTYPE html>
<html lang="en" x-data="{ open: false, notifOpen: false }" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Med System') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css'])

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body class="bg-gray-100">

@php
    $userRole = strtolower(auth()->user()->role ?? '');
    $dashboardRoute = match($userRole) {
        'admin'   => route('dashboard'),
        'nurse'   => route('nurse.dashboard'),
        'student' => route('student.dashboard'),
        default   => url('/'),
    };
    $appointmentsRoute = match($userRole) {
        'nurse'   => route('nurse.appointments.index'),
        'student' => route('student.appointments.index'),
        default   => null,
    };
@endphp

{{-- Navbar --}}
<nav class="bg-blue-600 border-b border-blue-500 shadow fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                {{-- Logo --}}
                <a href="{{ $dashboardRoute }}" class="flex items-center text-white font-bold text-lg">
                    <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v3h6v-3c0-1.657-1.343-3-3-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12v8h16v-8M2 12l10-9 10 9" />
                    </svg>
                    MedCare
                </a>

                {{-- Desktop Links --}}
                <div class="hidden sm:flex sm:space-x-6 sm:ml-10">
                    <a href="{{ $dashboardRoute }}" class="text-white hover:text-gray-200 font-medium">Dashboard</a>
                    @if($appointmentsRoute)
                    <a href="{{ $appointmentsRoute }}" class="text-white hover:text-gray-200 font-medium">Appointments</a>
                    @endif
                </div>
            </div>

            {{-- Right Side --}}
            <div class="hidden sm:flex sm:items-center space-x-4">
                {{-- Notification Bell --}}
                <div class="relative" x-data="{ openNotif: false }">
                    <button @click="openNotif = !openNotif" class="text-white hover:text-gray-200 focus:outline-none relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span id="notifBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1 hidden">0</span>
                    </button>

                    {{-- Dropdown for notifications --}}
                    <div x-show="openNotif" x-cloak
                        class="absolute right-0 mt-2 w-80 bg-white border rounded shadow-lg overflow-hidden z-50">
                        <div class="p-2 text-sm font-medium text-gray-700 bg-gray-100 border-b">Notifications</div>
                        <div id="notifList" class="max-h-60 overflow-y-auto">
                            <div class="p-2 text-gray-500 text-sm">No notifications</div>
                        </div>
                    </div>
                </div>

                {{-- User Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-white hover:text-gray-200 focus:outline-none">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger (Mobile) --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-white hover:bg-blue-700 focus:outline-none">
                    <svg :class="{'hidden': open, 'block': !open}" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg :class="{'hidden': !open, 'block': open}" class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="sm:hidden bg-blue-500 z-50"
    >
        <div class="px-2 pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="$dashboardRoute" class="text-white text-sm">Dashboard</x-responsive-nav-link>
            @if($appointmentsRoute)
            <x-responsive-nav-link :href="$appointmentsRoute" class="text-white text-sm">Appointments</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-blue-400 px-2 py-3">
            <div class="text-white font-medium text-sm">{{ Auth::user()->name }}</div>
            <div class="text-blue-200 text-xs">{{ Auth::user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-white text-sm">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>

{{-- Toast Container --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>

{{-- Main Content --}}
<main class="pt-20 pb-8 min-h-screen bg-dashboard-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @yield('content')
    </div>
</main>

{{-- Footer --}}
<footer class="bg-blue-600 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-center">
        <p class="text-sm">&copy; {{ date('Y') }} MedCare System. All rights reserved.</p>
        <div class="flex space-x-4 mt-3 sm:mt-0 text-sm">
            <span>v1.0</span>
            <a href="#" class="hover:underline">Privacy</a>
            <a href="#" class="hover:underline">Terms</a>
        </div>
    </div>
</footer>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Laravel flash messages
    window.flashMessages = {
        success: "{{ session('success') }}",
        error: "{{ session('error') }}",
        warning: "{{ session('warning') }}",
        info: "{{ session('info') }}"
    };
    if (window.flashMessages.success) toastr.success(window.flashMessages.success);
    if (window.flashMessages.error) toastr.error(window.flashMessages.error);
    if (window.flashMessages.warning) toastr.warning(window.flashMessages.warning);
    if (window.flashMessages.info) toastr.info(window.flashMessages.info);

    // 🔔 Polling fallback (every 5s)
    function checkNotifications() {
        axios.get("{{ route('notifications.check') }}")
            .then(response => {
                const data = response.data;
                const badge = document.getElementById('notifBadge');
                const notifList = document.getElementById('notifList');

                if (data.count > 0) {
                    badge.innerText = data.count;
                    badge.classList.remove('hidden');

                    notifList.innerHTML = ''; // clear old list
                    data.notifications.forEach(n => {
                        const notifItem = document.createElement('div');
                        notifItem.className = 'p-2 text-sm text-gray-700 border-b hover:bg-gray-100 cursor-pointer';
                        notifItem.innerText = n.message;
                        notifList.prepend(notifItem);
                    });
                } else {
                    badge.classList.add('hidden');
                    notifList.innerHTML = `<div class="p-2 text-gray-500 text-sm">No notifications</div>`;
                }
            })
            .catch(err => console.error("Polling error:", err));
    }
    setInterval(checkNotifications, 5000);
    checkNotifications();

    // 🔔 Echo listener (if enabled)
    if (window.Echo) {
        window.Echo.channel('notifications')
            .listen('NewNotification', (e) => {
                const message = e.message;

                // Toast popup
                const toastContainer = document.getElementById('toastContainer');
                const toastHTML = `
                    <div class="toast align-items-center text-bg-info border-0 mb-2" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>`;
                toastContainer.insertAdjacentHTML('beforeend', toastHTML);
                new bootstrap.Toast(toastContainer.lastElementChild).show();

                // Bell dropdown
                const notifList = document.getElementById('notifList');
                const placeholder = notifList.querySelector('.text-gray-500');
                if (placeholder) placeholder.remove();

                const notifItem = document.createElement('div');
                notifItem.className = 'p-2 text-sm text-gray-700 border-b hover:bg-gray-100 cursor-pointer';
                notifItem.innerText = message;
                notifList.prepend(notifItem);

                // Update badge count
                const badge = document.getElementById('notifBadge');
                let count = parseInt(badge.innerText || "0");
                badge.innerText = count + 1;
                badge.classList.remove('hidden');
            });
    }
</script>




<style>
    .bg-dashboard-gradient {
        background: linear-gradient(to bottom right, #d1fae5, #ffffff, #cffafe);
    }
</style>
{{-- ✅ Success/Error Modal (Centered, persistent until closed) --}}
@if(session()->has('success') || session()->has('error'))
<div 
    x-data="{ show: true }" 
    x-show="show"
    x-init="
        setTimeout(() => show = false, 8000);
        // Remove session messages from localStorage
        fetch('{{ route('clear.session.messages') }}');
    "
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
