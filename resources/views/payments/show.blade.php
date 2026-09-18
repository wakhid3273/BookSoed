<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pembayaran #{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('orders.show', $payment->order) }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Order</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            {{-- Ringkasan Pembayaran --}}
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <div class="flex justify-between items-start border-b pb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Status Pembayaran</h3>
                        <p class="text-xs text-gray-400 mt-1">Order #{{ str_pad($payment->order_id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    @include('payments._status-badge', ['status' => $payment->payment_status])
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm pt-2">
                    <div>
                        <p class="text-gray-400">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-800">
                            {{ $payment->payment_method }}
                            @if ($payment->ewallet_provider)
                                <span class="text-xs font-normal text-gray-500">({{ $payment->ewallet_provider }})</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400">Total Tagihan</p>
                        <p class="font-bold text-indigo-600 text-base">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    @if ($payment->transaction_reference)
                        <div>
                            <p class="text-gray-400">Referensi Transaksi</p>
                            <p class="font-mono text-xs text-gray-700">{{ $payment->transaction_reference }}</p>
                        </div>
                    @endif

                    @if ($payment->paid_at)
                        <div>
                            <p class="text-gray-400">Waktu Pembayaran</p>
                            <p class="text-xs text-gray-700">{{ $payment->paid_at->format('d M Y, H:i:s') }}</p>
                        </div>
                    @endif
                </div>

                @if ($payment->payment_method === 'COD' && $payment->isPending())
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800">
                        <p class="font-semibold mb-1">Petunjuk COD (Cash on Delivery):</p>
                        <p>Pembayaran akan dilakukan secara tunai kepada penjual saat serah terima buku. Status order akan diperbarui setelah transaksi selesai.</p>
                    </div>
                @endif
            </div>

            {{-- Panel Simulasi E-Wallet (Hanya tampil jika PENDING dan E-WALLET) --}}
            @if ($payment->isPending() && $payment->payment_method === 'E-WALLET' && auth()->id() === $payment->order->buyer_id)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 space-y-4">
                    <h4 class="font-bold text-amber-900 text-sm">Simulasi Payment Gateway (Pengujian Modul ERP)</h4>
                    <p class="text-xs text-amber-700">
                        Pilih aksi di bawah ini untuk mensimulasikan respons dari payment provider {{ $payment->ewallet_provider }}:
                    </p>

                    <div class="flex gap-3">
                        <form action="{{ route('payments.simulate-success', $payment) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md transition">
                                ✓ Sukseskan Pembayaran
                            </button>
                        </form>

                        <form action="{{ route('payments.simulate-fail', $payment) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md transition">
                                ✗ Gagalkan Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Jika status FAILED, berikan opsi Coba Lagi --}}
            @if ($payment->isFailed() && auth()->id() === $payment->order->buyer_id)
                <div class="bg-white rounded-xl shadow p-6 text-center space-y-3">
                    <p class="text-sm text-gray-600">Pembayaran sebelumnya gagal. Anda dapat memilih kembali metode pembayaran.</p>
                    <a href="{{ route('payments.create', $payment->order) }}"
                       class="inline-block px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        Coba Bayar Lagi
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
