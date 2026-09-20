<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Profil Civitas</h1>
                    <p class="text-sm text-warm-700 mt-1">Dashboard aktivitas transaksi dan kelola preferensi buku kamu</p>
                </div>
                <a href="{{ route('crm.wishlist') }}" class="btn-sm-primary shrink-0">
                    ❤️ Wishlist Saya
                </a>
            </div>

            {{-- ── FLASH MESSAGE ───────────────────────────────────────── --}}
            @if(session('crm_status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2"
                     id="crm-flash-alert" role="alert">
                    ✓ {{ session('crm_status') }}
                </div>
            @endif

            {{-- ── PROFILE HERO CARD ───────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-6" id="profile-hero-card">
                <div class="flex items-center gap-5 flex-wrap">
                    {{-- Avatar --}}
                    <div class="w-16 h-16 rounded-full bg-brand-700 text-white flex items-center justify-center
                                font-serif text-2xl font-bold shrink-0 shadow-md"
                         id="dashboard-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    {{-- Profile Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap mb-1">
                            <h2 class="font-serif text-xl font-bold text-warm-900">{{ Auth::user()->name }}</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                         bg-brand-50 text-brand-700 border border-brand-200">
                                ✓ Civitas Unsoed
                            </span>
                        </div>
                        <p class="text-sm text-warm-600">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-warm-500 mt-0.5">Mahasiswa / Civitas Akademika Universitas Jenderal Soedirman</p>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex gap-2 flex-wrap shrink-0">
                        <a href="{{ route('crm.wishlist') }}"
                           class="btn-secondary text-sm px-4 py-2"
                           id="btn-wishlist-hero">
                            ❤️ Wishlist
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="btn-secondary text-sm px-4 py-2">
                            ⚙️ Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── STATISTICS ──────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="stats-row">

                {{-- Stat: Pembelian Selesai --}}
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-6 text-center reveal-card"
                     id="stat-purchases">
                    <div class="font-serif text-4xl font-bold text-brand-700 leading-none">{{ $purchaseCount }}</div>
                    <div class="text-xs text-warm-500 mt-2 uppercase tracking-widest font-semibold">Pembelian Selesai</div>
                    <div class="mt-3 text-2xl opacity-30">🛒</div>
                </div>

                {{-- Stat: Buku Terjual --}}
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-6 text-center reveal-card"
                     id="stat-sales">
                    <div class="font-serif text-4xl font-bold text-brand-700 leading-none">{{ $salesCount }}</div>
                    <div class="text-xs text-warm-500 mt-2 uppercase tracking-widest font-semibold">Buku Terjual</div>
                    <div class="mt-3 text-2xl opacity-30">📚</div>
                </div>

                {{-- Stat: Wishlist --}}
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-6 text-center reveal-card"
                     id="stat-wishlist">
                    <div class="font-serif text-4xl font-bold text-brand-700 leading-none">{{ $wishlistCount }}</div>
                    <div class="text-xs text-warm-500 mt-2 uppercase tracking-widest font-semibold">Wishlist</div>
                    <div class="mt-3 text-2xl opacity-30">❤️</div>
                </div>
            </div>

            {{-- ── HISTORY SECTION (2 kolom) ───────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="history-grid">

                {{-- ── RIWAYAT PEMBELIAN ───────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden"
                     id="purchase-history-card">
                    <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-warm-900 text-sm">Riwayat Pembelian</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                     bg-warm-100 text-warm-700 border border-warm-200">
                            {{ $purchaseCount }} total
                        </span>
                    </div>

                    <div class="divide-y divide-warm-100">
                        @forelse($purchaseHistory as $order)
                            <div class="p-4 flex items-center gap-4 hover:bg-warm-50/50 transition"
                                 id="purchase-row-{{ $order->order_id }}">
                                {{-- Book icon --}}
                                <div class="w-9 h-12 rounded bg-brand-50 border border-brand-100
                                            flex items-center justify-center shrink-0 text-brand-600 text-sm">
                                    📖
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-warm-900 truncate">
                                        {{ $order->orderItems->first()?->book?->title ?? 'Pesanan #'.$order->order_id }}
                                    </div>
                                    <div class="text-xs text-warm-500 mt-0.5">
                                        {{ $order->order_date?->format('d M Y') }}
                                    </div>
                                </div>

                                {{-- Status & Review --}}
                                <div class="flex flex-col items-end gap-1.5 shrink-0">
                                    @php
                                        $statusClasses = match($order->order_status) {
                                            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                            default     => 'bg-warm-100 text-warm-700 border-warm-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    @if($order->order_status === 'completed' && !$order->review)
                                        <a href="{{ route('crm.review.create', $order) }}"
                                           class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-semibold
                                                  bg-brand-50 text-brand-700 border border-brand-200
                                                  hover:bg-brand-100 transition"
                                           id="btn-review-{{ $order->order_id }}">
                                            ⭐ Beri Ulasan
                                        </a>
                                    @elseif($order->review)
                                        <span class="text-xs text-amber-600 font-semibold">
                                            ★ {{ $order->review->rating }}/5
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center" id="purchase-empty">
                                <div class="text-3xl mb-2 opacity-30">🛒</div>
                                <p class="text-sm text-warm-500">Belum ada pembelian</p>
                                <a href="{{ route('books.index') }}"
                                   class="inline-block mt-3 text-xs text-brand-700 font-semibold hover:underline">
                                    Jelajahi Marketplace →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── RIWAYAT PENJUALAN ───────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden"
                     id="sales-history-card">
                    <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-warm-900 text-sm">Riwayat Penjualan</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                     bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ $salesCount }} terjual
                        </span>
                    </div>

                    <div class="divide-y divide-warm-100">
                        @forelse($salesHistory as $order)
                            <div class="p-4 flex items-center gap-4 hover:bg-warm-50/50 transition"
                                 id="sales-row-{{ $order->order_id }}">
                                {{-- Book icon --}}
                                <div class="w-9 h-12 rounded bg-emerald-50 border border-emerald-100
                                            flex items-center justify-center shrink-0 text-emerald-600 text-sm">
                                    📚
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-warm-900 truncate">
                                        {{ $order->orderItems->first()?->book?->title ?? 'Pesanan #'.$order->order_id }}
                                    </div>
                                    <div class="text-xs text-warm-500 mt-0.5">
                                        Pembeli: {{ $order->buyer?->name }}
                                        &bull; {{ $order->order_date?->format('d M Y') }}
                                    </div>
                                </div>

                                {{-- Status & Total --}}
                                <div class="flex flex-col items-end gap-1.5 shrink-0">
                                    @php
                                        $statusClasses = match($order->order_status) {
                                            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
                                            default     => 'bg-warm-100 text-warm-700 border-warm-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                    <span class="text-xs font-semibold text-warm-700">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center" id="sales-empty">
                                <div class="text-3xl mb-2 opacity-30">📚</div>
                                <p class="text-sm text-warm-500">Belum ada penjualan</p>
                                <a href="{{ route('books.create') }}"
                                   class="inline-block mt-3 text-xs text-brand-700 font-semibold hover:underline">
                                    Mulai Jual Buku →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-dismiss flash alert
        const alert = document.getElementById('crm-flash-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.4s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 400);
            }, 4000);
        }
    </script>
    @endpush
</x-app-layout>
