@php
    $navItems = [
        ['route' => 'admin.dashboard',      'label' => '🏠 Dashboard',    'active' => request()->routeIs('admin.dashboard')],
        ['route' => 'admin.users.index',    'label' => '👥 Pengguna',     'active' => request()->routeIs('admin.users.*')],
        ['route' => 'admin.books.index',    'label' => '📚 Listing Buku', 'active' => request()->routeIs('admin.books.*')],
        ['route' => 'admin.orders.index',   'label' => '📦 Order',        'active' => request()->routeIs('admin.orders.*')],
        ['route' => 'admin.payments.index', 'label' => '💳 Pembayaran',   'active' => request()->routeIs('admin.payments.*')],
    ];
@endphp

<div class="border-b border-warm-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-1 overflow-x-auto py-2 scrollbar-hide" aria-label="Admin navigation">
            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 border border-amber-200 px-2 py-1 rounded-md shrink-0 mr-2">
                ADMIN ERP
            </span>
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="shrink-0 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 whitespace-nowrap
                          {{ $item['active']
                             ? 'bg-brand-700 text-white shadow-sm'
                             : 'text-warm-700 hover:bg-warm-100 hover:text-warm-900' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
