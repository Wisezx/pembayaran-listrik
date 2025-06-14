<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:flex sm:ml-10 text-sm font-medium">
                    @if (Auth::user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:text-gray-500">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pelanggan.index')" :active="request()->routeIs('pelanggan.index')" class="hover:text-gray-500">
                            {{ __('Pelanggan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.index')" :active="request()->routeIs('petugas.index')" class="hover:text-gray-500">
                            {{ __('Petugas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tarif.index')" :active="request()->routeIs('tarif.index')" class="hover:text-gray-500">
                            {{ __('Tarif') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pemakaian.index')" :active="request()->routeIs('pemakaian.index')" class="hover:text-gray-500">
                            {{ __('Pemakaian') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pembayaran.index')" :active="request()->routeIs('pembayaran.index')" class="hover:text-gray-500">
                            {{ __('Pembayaran') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'petugas')
                        <x-nav-link :href="route('pelanggan.index')" :active="request()->routeIs('pelanggan.index')" class="hover:text-gray-500">
                            {{ __('Pelanggan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pembayaran.index')" :active="request()->routeIs('pembayaran.index')" class="hover:text-gray-500">
                            {{ __('Pembayaran') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-500 focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414L10 13.414l-4.707-4.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
