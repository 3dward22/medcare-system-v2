<nav x-data="{ open: false }" class="bg-blue-600 border-b border-blue-500 shadow fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo + Desktop Links -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center text-white font-bold text-lg">
                    <svg class="h-5 w-5 mr-2 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v3h6v-3c0-1.657-1.343-3-3-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12v8h16v-8M2 12l10-9 10 9" />
                    </svg>
                    MedCare
                </a>

                <!-- Desktop Links -->
                <div class="hidden sm:flex sm:space-x-6 sm:ml-10">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200 font-medium">
                        Dashboard
                    </a>
                    @if($appointmentsRoute)
                        <a href="{{ $appointmentsRoute }}" class="text-white hover:text-gray-200 font-medium">
                            Appointments
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Side (Desktop) -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-white hover:text-gray-200 focus:outline-none">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="flex items-center sm:hidden -mr-2">
                <button @click="open = !open" class="p-2 rounded-md text-white hover:bg-blue-700 focus:outline-none">
                    <!-- Hamburger Icon -->
                    <svg :class="{'hidden': open, 'block': !open}" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <!-- Close Icon -->
                    <svg :class="{'hidden': !open, 'block': open}" class="hidden h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div 
        x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="sm:hidden bg-blue-500 w-full absolute z-50"
    >
        <div class="space-y-1 px-2 py-3">
            <x-responsive-nav-link :href="route('dashboard')" class="text-white text-sm">
                Dashboard
            </x-responsive-nav-link>

            @if($appointmentsRoute)
                <x-responsive-nav-link :href="$appointmentsRoute" class="text-white text-sm">
                    Appointments
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Mobile Profile / Logout -->
        <div class="border-t border-blue-400 px-2 py-3">
            <div class="text-white font-medium text-sm">{{ Auth::user()->name }}</div>
            <div class="text-blue-200 text-xs">{{ Auth::user()->email }}</div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-white text-sm">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
