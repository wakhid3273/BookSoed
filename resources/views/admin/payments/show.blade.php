<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between">
                <x-back-button :href="route('admin.payments.index')" label="Kembali ke Pembayaran" />
                <span class="text-xs font-mono font-semibold text-warm-600 bg-warm-100 px-3 py-1 rounded-lg">
                    Payment #{{ $payment->id }}
                </span>
            </div>

            <!-- Payment Header Card -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-3 border-b border-warm-100 pb-4">
                    <div>
                        <h1 class="font-serif text-2xl font-bold text-warm-900">Detail Pembayaran</h1>
                        <p class="text-xs text-warm-500 mt-1">
                            Order #BS-{{ str_pad($payment->order->id, 5, '0', STR_PAD_LEFT) }} ·
                            {{ $payment->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                    @include('payments._status-badge', ['status' => $payment->payment_status])
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Metode</span>
                        <span class="font-semibold text-warm-900 text-sm">{{ $payment->payment_method }}</span>
                    </div>
                    @if ($payment->ewallet_provider)
                        <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                            <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Provider</span>
                            <span class="font-semibold text-warm-900 text-sm">{{ $payment->ewallet_provider }}</span>
                        </div>
                    @endif
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Jumlah</span>
                        <span class="font-bold text-brand-700 text-base">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($payment->transaction_reference)
                        <div class="col-span-2 sm:col-span-3 bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                            <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Referensi Transaksi</span>
                            <span class="font-mono text-warm-800 text-xs break-all">{{ $payment->transaction_reference }}</span>
                        </div>
                    @endif
                    @if ($payment->paid_at)
                        <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                            <span class="text-warm-400 text-[11px] uppercase tracking-wider font-semibold block mb-1">Waktu Pembayaran</span>
                            <span class="font-semibold text-warm-900 text-sm">{{ $payment->paid_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Order Summary -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <h3 class="font-bold text-warm-900 text-sm border-b border-warm-100 pb-3">Informasi Order Terkait</h3>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-warm-400 block mb-0.5">Buyer</span>
                        <span class="font-semibold text-warm-900">{{ $payment->order->buyer->name }}</span>
                        <span class="text-warm-500 block text-[11px]">{{ $payment->order->buyer->email }}</span>
                    </div>
                    <div>
                        <span class="text-warm-400 block mb-0.5">Seller</span>
                        <span class="font-semibold text-warm-900">{{ $payment->order->seller->name }}</span>
                        <span class="text-warm-500 block text-[11px]">{{ $payment->order->seller->email }}</span>
                    </div>
                    <div>
                        <span class="text-warm-400 block mb-0.5">Status Order</span>
                        @include('orders._status-badge', ['status' => $payment->order->status])
                    </div>
                    <div>
                        <span class="text-warm-400 block mb-0.5">Total Order</span>
                        <span class="font-bold text-brand-700 text-sm">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Items mini list -->
                <div class="border-t border-warm-100 pt-3 space-y-2">
                    @foreach ($payment->order->items as $item)
                        <div class="flex justify-between text-xs text-warm-700">
                            <span class="truncate pr-4">{{ $item->book->title ?? '(Buku dihapus)' }}</span>
                            <span class="font-semibold shrink-0">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('admin.orders.show', $payment->order) }}"
                   class="btn-secondary text-xs">
                    Lihat Detail Order →
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
