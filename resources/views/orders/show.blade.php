<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Breadcrumb & Back --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">
                    ← Kembali ke Order Saya
                </a>
                <span class="text-xs font-mono text-warm-500">Order ID: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Order Header Card --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex justify-between items-start border-b border-warm-200 pb-4">
                    <div>
                        <h1 class="font-serif text-2xl font-bold text-warm-900">Detail Rincian Order</h1>
                        <p class="text-xs text-warm-500 mt-1">Dibuat pada {{ $order->order_date->format('d M Y, H:i') }} WIB</p>
                    </div>
                    @include('orders._status-badge', ['status' => $order->status])
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs pt-1">
                    <div>
                        <span class="text-warm-500 block mb-0.5">Penjual (Seller)</span>
                        <span class="font-semibold text-warm-900 text-sm">👤 {{ $order->seller->name }}</span>
                    </div>
                    <div>
                        <span class="text-warm-500 block mb-0.5">Pembeli (Buyer)</span>
                        <span class="font-semibold text-warm-900 text-sm">👤 {{ $order->buyer->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-200 bg-warm-50/50">
                    <h3 class="font-bold text-warm-900 text-sm">Daftar Buku Dipesan</h3>
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

                {{-- Order Summary Totals --}}
                <div class="p-6 bg-warm-50/80 border-t border-warm-200 space-y-2 text-xs">
                    <div class="flex justify-between text-warm-600">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-warm-600">
                        <span>Service Fee (Biaya Layanan ERP)</span>
                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-warm-900 text-base border-t border-warm-200 pt-3 mt-2">
                        <span>Total Pembayaran</span>
                        <span class="text-brand-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[11px] text-warm-500 pt-1">
                        * Catatan: Pengiriman / Fulfillment COD akan ditangani oleh modul SCM.
                    </p>
                </div>
            </div>

            {{-- Payment Status & Actions --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-warm-900 text-sm">Informasi Pembayaran</h3>
                        @if ($order->payment)
                            <div class="mt-1 flex items-center gap-2 text-xs">
                                <span class="text-warm-600 font-medium">Metode: {{ $order->payment->payment_method }} {{ $order->payment->ewallet_provider ? '('.$order->payment->ewallet_provider.')' : '' }}</span>
                                @include('payments._status-badge', ['status' => $order->payment->payment_status])
                            </div>
                        @else
                            <p class="text-xs text-warm-500 mt-1">Belum ada pembayaran dilakukan untuk order ini.</p>
                        @endif
                    </div>

                    @if ($order->payment)
                        <a href="{{ route('payments.show', $order->payment) }}"
                           class="px-4 py-2 bg-warm-100 hover:bg-warm-200 text-warm-800 text-xs font-semibold rounded-xl transition">
                            Lihat Pembayaran →
                        </a>
                    @elseif (auth()->id() === $order->buyer_id && $order->status !== 'CANCELLED')
                        <a href="{{ route('payments.create', $order) }}"
                           class="px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-xs rounded-xl shadow-xs transition duration-150">
                            💳 Bayar Sekarang
                        </a>
                    @endif
                </div>
            </div>

            {{-- Cancel Order Action --}}
            @if ($order->status === 'PENDING' && auth()->id() === $order->buyer_id)
                <div class="pt-2 flex justify-end">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan order ini? Buku akan dikembalikan ke status TERSEDIA di marketplace.')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-semibold rounded-xl transition">
                            🚫 Batalkan Order Ini
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
