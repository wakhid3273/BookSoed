<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between">
                <x-back-button :href="route('admin.orders.index')" label="Kembali ke Order" />
                <span class="text-xs font-mono font-semibold text-warm-600 bg-warm-100 px-3 py-1 rounded-lg">
                    #BS-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <!-- Header Card -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-warm-100 pb-4">
                    <div>
                        <h1 class="font-serif text-2xl font-bold text-warm-900">Detail Order</h1>
                        <p class="text-xs text-warm-500 mt-1">{{ $order->order_date->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @include('orders._status-badge', ['status' => $order->status])
                        @if ($order->payment)
                            @include('payments._status-badge', ['status' => $order->payment->payment_status])
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Buyer</span>
                        <p class="font-semibold text-warm-900 text-sm">{{ $order->buyer->name }}</p>
                        <p class="text-warm-500 text-[11px]">{{ $order->buyer->email }}</p>
                    </div>
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Seller</span>
                        <p class="font-semibold text-warm-900 text-sm">{{ $order->seller->name }}</p>
                        <p class="text-warm-500 text-[11px]">{{ $order->seller->email }}</p>
                    </div>
                </div>

                @if ($order->payment)
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100 text-xs">
                        <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-2">Informasi Pembayaran</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <span class="text-warm-400 block">Metode</span>
                                <span class="font-semibold text-warm-800">{{ $order->payment->payment_method }}</span>
                            </div>
                            @if ($order->payment->ewallet_provider)
                                <div>
                                    <span class="text-warm-400 block">Provider</span>
                                    <span class="font-semibold text-warm-800">{{ $order->payment->ewallet_provider }}</span>
                                </div>
                            @endif
                            @if ($order->payment->transaction_reference)
                                <div>
                                    <span class="text-warm-400 block">Ref. Transaksi</span>
                                    <span class="font-mono text-warm-800 text-[11px]">{{ $order->payment->transaction_reference }}</span>
                                </div>
                            @endif
                            @if ($order->payment->paid_at)
                                <div>
                                    <span class="text-warm-400 block">Waktu Bayar</span>
                                    <span class="font-semibold text-warm-800">{{ $order->payment->paid_at->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50 flex justify-between">
                    <h3 class="font-bold text-warm-900 text-sm">Buku yang Dipesan</h3>
                    <span class="text-xs text-warm-500">{{ $order->items->count() }} item</span>
                </div>
                <div class="divide-y divide-warm-100">
                    @foreach ($order->items as $item)
                        <div class="p-5 flex items-start gap-4">
                            <div class="w-12 h-14 rounded-lg bg-warm-100 border border-warm-200 overflow-hidden shrink-0 flex items-center justify-center text-warm-400">
                                @if ($item->book && $item->book->photo_path)
                                    <img src="{{ asset('storage/' . $item->book->photo_path) }}" alt="{{ $item->book->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-lg">📚</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-warm-900 text-sm">{{ $item->book->title ?? '(Buku telah dihapus)' }}</h4>
                                @if ($item->book)
                                    <p class="text-xs text-warm-500 mt-0.5">{{ $item->book->author }} · {{ $item->book->category->name ?? '-' }}</p>
                                @endif
                                <p class="text-xs text-warm-500 mt-0.5">Qty: <span class="font-semibold text-warm-800">{{ $item->quantity }}</span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-warm-400 block">Harga Transaksi</span>
                                <span class="font-bold text-warm-900 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Summary -->
                <div class="p-5 bg-warm-50/70 border-t border-warm-200 space-y-2 text-xs">
                    <div class="flex justify-between text-warm-600">
                        <span>Subtotal Produk</span>
                        <span class="font-medium text-warm-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-warm-600">
                        <span>Service Fee Platform</span>
                        <span class="font-medium text-warm-900">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-base text-warm-900 border-t border-warm-200 pt-3 mt-1">
                        <span>Total Order</span>
                        <span class="text-brand-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
