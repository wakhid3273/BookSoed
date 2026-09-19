<x-app-layout>
    <div class="py-8 page-enter">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Back Button Header -->
            <div class="flex items-center justify-between">
                <x-back-button :href="route('seller.orders.index')" label="Kembali ke Pesanan Masuk" />
                <span class="text-xs font-mono font-semibold text-warm-600 bg-warm-100 px-3 py-1 rounded-lg">
                    Order #BS-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Main Order Header -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-warm-100 pb-4">
                    <div>
                        <h1 class="font-serif text-2xl font-bold text-warm-900">Detail Pesanan</h1>
                        <p class="text-xs text-warm-500 mt-1">Diterima pada {{ $order->order_date->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-warm-500 font-medium">Status Order:</span>
                        @include('orders._status-badge', ['status' => $order->status])
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1">
                    <div class="bg-warm-50/70 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-500 block text-[11px] uppercase tracking-wider font-semibold mb-1">Informasi Pembeli</span>
                        <div class="flex items-center gap-2 font-semibold text-warm-900 text-sm">
                            <span class="w-6 h-6 rounded-full bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-xs">👤</span>
                            <span>{{ $order->buyer->name }}</span>
                        </div>
                        <span class="text-warm-500 text-[11px] block mt-1">{{ $order->buyer->email }}</span>
                    </div>
                    <div class="bg-warm-50/70 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-500 block text-[11px] uppercase tracking-wider font-semibold mb-1">Informasi Pembayaran</span>
                        @if ($order->payment)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-semibold text-warm-900 text-sm">
                                    {{ $order->payment->payment_method }}
                                    @if ($order->payment->ewallet_provider)
                                        ({{ $order->payment->ewallet_provider }})
                                    @endif
                                </span>
                                @include('payments._status-badge', ['status' => $order->payment->payment_status])
                            </div>
                        @else
                            <p class="text-xs text-warm-500 mt-1">Belum ada catatan pembayaran.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items List -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-warm-900 text-sm">Daftar Buku Dipesan</h3>
                    <span class="text-xs text-warm-500 font-medium">{{ $order->items->count() }} item</span>
                </div>
                <div class="divide-y divide-warm-100">
                    @foreach ($order->items as $item)
                        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4 min-w-0">
                                <div class="w-14 h-16 rounded-lg bg-warm-100 border border-warm-200 overflow-hidden shrink-0 flex items-center justify-center text-warm-400">
                                    @if ($item->book && $item->book->image_path)
                                        <img src="{{ asset('storage/' . $item->book->image_path) }}" alt="{{ $item->book->title }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl">📚</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-warm-900 text-sm leading-snug">
                                        {{ $item->book->title ?? '(Buku telah dihapus)' }}
                                    </h4>
                                    @if ($item->book)
                                        <p class="text-xs text-warm-500 mt-1">
                                            Penulis: <span class="font-medium text-warm-700">{{ $item->book->author }}</span>
                                            · Kategori: <span class="font-medium text-warm-700">{{ $item->book->category->name ?? '-' }}</span>
                                        </p>
                                    @endif
                                    <div class="text-xs text-warm-500 mt-1">
                                        Jumlah: <span class="font-semibold text-warm-800">{{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right sm:self-center w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-warm-100">
                                <span class="text-[11px] text-warm-500 block sm:hidden">Harga Transaksi</span>
                                <span class="font-bold text-warm-900 text-base">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary Breakdown -->
                <div class="p-6 bg-warm-50/70 border-t border-warm-200 space-y-2.5 text-xs">
                    <div class="flex justify-between text-warm-600">
                        <span>Subtotal Produk</span>
                        <span class="font-medium text-warm-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-warm-600">
                        <span>Service Fee (Biaya Layanan Platform)</span>
                        <span class="font-medium text-warm-900">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-warm-900 text-base border-t border-warm-200 pt-3 mt-2">
                        <span>Total Pesanan</span>
                        <span class="text-brand-700 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Seller Action Box -->
            @if ($order->status === 'PENDING')
                <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-6 space-y-4 shadow-xs" x-data="{ submitting: false }">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h4 class="font-bold text-amber-950 text-base">Konfirmasi & Proses Pesanan</h4>
                            <p class="text-xs text-amber-800 leading-relaxed mt-1">
                                Klik tombol di bawah untuk memproses pesanan ini. Status pesanan akan diperbarui dari <strong>PENDING</strong> menjadi <strong>PROCESSING</strong>.
                            </p>
                        </div>
                    </div>
                    <form action="{{ route('seller.orders.process', $order) }}" method="POST" @submit="submitting = true">
                        @csrf
                        <button type="submit"
                                :disabled="submitting"
                                class="btn-primary w-full sm:w-auto">
                            <span x-show="!submitting" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Proses Pesanan
                            </span>
                            <span x-show="submitting" x-cloak class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
