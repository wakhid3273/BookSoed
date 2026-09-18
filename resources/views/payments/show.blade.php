<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between">
                <x-back-button :href="route('orders.show', $payment->order)" label="← Kembali ke Detail Order" />
                <span class="text-xs font-mono text-warm-500">ID Pembayaran: #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-medium">
                    ✕ {{ session('error') }}
                </div>
            @endif

            {{-- Ringkasan Pembayaran --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8 space-y-6">
                <div class="flex justify-between items-start border-b border-warm-200 pb-4">
                    <div>
                        <h1 class="font-serif text-2xl font-bold text-warm-900">Detail Pembayaran</h1>
                        <p class="text-xs text-warm-500 mt-0.5">Order #{{ str_pad($payment->order_id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    @include('payments._status-badge', ['status' => $payment->payment_status])
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-warm-500 block mb-1">Metode Pembayaran</span>
                        <span class="font-bold text-warm-900 text-sm">
                            {{ $payment->payment_method }}
                            @if ($payment->ewallet_provider)
                                <span class="text-xs font-normal text-warm-600">({{ $payment->ewallet_provider }})</span>
                            @endif
                        </span>
                    </div>

                    <div>
                        <span class="text-warm-500 block mb-1">Total Tagihan</span>
                        <span class="font-bold text-brand-700 text-lg">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </span>
                    </div>

                    @if ($payment->transaction_reference)
                        <div class="col-span-2 sm:col-span-1">
                            <span class="text-warm-500 block mb-1">Kode / Referensi Transaksi</span>
                            <span class="font-mono text-xs font-semibold text-warm-800 bg-warm-100 px-2 py-1 rounded">
                                {{ $payment->transaction_reference }}
                            </span>
                        </div>
                    @endif

                    @if ($payment->paid_at)
                        <div class="col-span-2 sm:col-span-1">
                            <span class="text-warm-500 block mb-1">Waktu Pelunasan</span>
                            <span class="text-xs font-medium text-warm-800">
                                {{ $payment->paid_at->format('d M Y, H:i:s') }} WIB
                            </span>
                        </div>
                    @endif
                </div>

                @if ($payment->payment_method === 'COD' && $payment::STATUS_PENDING === $payment->payment_status)
                    <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl text-xs text-sky-900 space-y-1">
                        <p class="font-bold text-sm">ℹ️ Petunjuk Transaksi COD (Cash on Delivery):</p>
                        <p class="leading-relaxed">
                            Pembayaran sebesar <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong> diserahkan secara tunai kepada penjual/kurir saat buku diterima. Status pesanan akan diproses oleh seller.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Panel Simulasi E-Wallet --}}
            @if ($payment->isPending() && $payment->payment_method === 'E-WALLET' && auth()->id() === $payment->order->buyer_id)
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 space-y-4">
                    <div>
                        <h4 class="font-bold text-amber-900 text-sm">🧪 Simulator Gateway E-Wallet (Modul ERP)</h4>
                        <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                            Simulasikan balasan webhooks/callback dari provider <strong>{{ $payment->ewallet_provider }}</strong>:
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <form action="{{ route('payments.simulate-success', $payment) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                ✓ Simulasikan Pembayaran Berhasil
                            </button>
                        </form>

                        <form action="{{ route('payments.simulate-fail', $payment) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 bg-rose-700 hover:bg-rose-800 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                                ✕ Simulasikan Pembayaran Gagal
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Retry Action on FAILED --}}
            @if ($payment->isFailed() && auth()->id() === $payment->order->buyer_id)
                <div class="bg-white rounded-2xl border border-warm-200 p-6 text-center space-y-3">
                    <p class="text-xs text-warm-600">Pembayaran E-Wallet sebelumnya gagal. Kamu dapat mengulang pembayaran.</p>
                    <a href="{{ route('payments.create', $payment->order) }}" class="btn-sm-primary text-xs px-5 py-2.5">
                        🔄 Coba Bayar Lagi
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
