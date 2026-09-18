<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between">
                <a href="{{ route('seller.orders.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">
                    ← Kembali ke Pesanan Masuk
                </a>
                <span class="text-xs font-mono text-warm-500">Order ID: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex justify-between items-start border-b border-warm-200 pb-4">
                    <div>
                        <h1 class="font-serif text-2xl font-bold text-warm-900">Kelola Pesanan Masuk</h1>
                        <p class="text-xs text-warm-500 mt-1">Order masuk dari {{ $order->buyer->name }}</p>
                    </div>
                    @include('orders._status-badge', ['status' => $order->status])
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs pt-1">
                    <div>
                        <span class="text-warm-500 block mb-0.5">Nama Pembeli (Buyer)</span>
                        <span class="font-semibold text-warm-900 text-sm">👤 {{ $order->buyer->name }}</span>
                    </div>
                    <div>
                        <span class="text-warm-500 block mb-0.5">Waktu Pemesanan</span>
                        <span class="font-semibold text-warm-900 text-sm">{{ $order->order_date->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-200 bg-warm-50/50">
                    <h3 class="font-bold text-warm-900 text-sm">Buku yang Dipesan</h3>
                </div>
                <div class="divide-y divide-warm-100">
                    @foreach ($order->items as $item)
                        <div class="p-6 flex items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-warm-900 text-sm">{{ $item->book->title ?? '(Buku telah dihapus)' }}</h4>
                                @if ($item->book)
                                    <p class="text-xs text-warm-500 mt-1">
                                        Penulis: {{ $item->book->author }} · Kategori: {{ $item->book->category->name ?? '-' }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right font-bold text-warm-900 text-sm">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($order->status === 'PENDING')
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 space-y-3">
                    <h4 class="font-bold text-amber-900 text-sm">Aksi Seller: Memproses Pesanan</h4>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Klik tombol di bawah untuk menyetujui dan mulai memproses pesanan ini. Status order akan berubah dari PENDING menjadi PROCESSING.
                    </p>
                    <form action="{{ route('seller.orders.process', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-xs rounded-xl shadow-xs transition duration-150">
                            ✓ Proses Pesanan Ini
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
