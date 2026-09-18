<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-warm-200 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('books.index') }}" class="transition hover:opacity-90">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:flex sm:items-center">
                    <a href="{{ route('books.index') }}"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('books.index') || request()->routeIs('books.show') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-warm-800 hover:text-brand-700 hover:bg-warm-100' }}">
                        Marketplace
                    </a>

                    @auth
                        <a href="{{ route('orders.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('orders.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-warm-800 hover:text-brand-700 hover:bg-warm-100' }}">
                            Order Saya
                        </a>
                        <a href="{{ route('books.my-listings') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('books.my-listings') || request()->routeIs('books.create') || request()->routeIs('books.edit') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-warm-800 hover:text-brand-700 hover:bg-warm-100' }}">
                            Listing Saya
                        </a>
                        <a href="{{ route('seller.orders.index') }}"
                           class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('seller.orders.*') ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-warm-800 hover:text-brand-700 hover:bg-warm-100' }}">
                            Pesanan Masuk
                        </a>
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-100 text-amber-900 font-semibold' : 'text-amber-800 hover:bg-amber-50' }}">
                                🛡️ Admin ERP
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Actions (Sell Book CTA & Profile / Auth) -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <a href="{{ route('books.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-medium text-sm rounded-lg shadow-sm hover:shadow transition duration-150">
                    <span>+</span> Jual Buku
                </a>

                @auth
                    <div class="ms-2">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-warm-200 text-sm font-medium rounded-lg text-warm-800 bg-warm-50 hover:bg-warm-100 focus:outline-none transition">
                                    <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-warm-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    ⚙️ Pengaturan Profil
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="text-red-600 hover:bg-red-50">
                                        🚪 Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center gap-2 border-l border-warm-200 pl-3">
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm font-medium text-warm-800 hover:text-brand-700 rounded-lg hover:bg-warm-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 rounded-lg transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center sm:hidden gap-2">
                <a href="{{ route('books.create') }}" class="px-3 py-1.5 bg-brand-700 text-white text-xs font-semibold rounded-md">
                    + Jual
                </a>
                <button @click="open = ! open" class="p-2 rounded-lg text-warm-800 hover:bg-warm-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-warm-50 border-t border-warm-200 px-4 pt-3 pb-4 space-y-2">
        <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('books.index') ? 'bg-brand-100 text-brand-800' : 'text-warm-800' }}">
            📖 Marketplace Buku
        </a>

        @auth
            <a href="{{ route('orders.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('orders.*') ? 'bg-brand-100 text-brand-800' : 'text-warm-800' }}">
                📦 Order Saya
            </a>
            <a href="{{ route('books.my-listings') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('books.my-listings') ? 'bg-brand-100 text-brand-800' : 'text-warm-800' }}">
                📚 Listing Saya
            </a>
            <a href="{{ route('seller.orders.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('seller.orders.*') ? 'bg-brand-100 text-brand-800' : 'text-warm-800' }}">
                📬 Pesanan Masuk
            </a>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium bg-amber-100 text-amber-900">
                    🛡️ Admin ERP
                </a>
            @endif

            <div class="pt-3 border-t border-warm-200">
                <div class="px-3 mb-2">
                    <p class="font-semibold text-warm-900 text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-warm-800">{{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-1.5 text-sm text-warm-800">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-1.5 text-sm text-red-600">Logout</button>
                </form>
            </div>
        @else
            <div class="pt-3 border-t border-warm-200 flex gap-2">
                <a href="{{ route('login') }}" class="flex-1 text-center py-2 bg-white border border-warm-200 text-warm-800 font-medium text-sm rounded-lg">Masuk</a>
                <a href="{{ route('register') }}" class="flex-1 text-center py-2 bg-brand-700 text-white font-medium text-sm rounded-lg">Daftar</a>
            </div>
        @endauth
    </div>
</nav>
