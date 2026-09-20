<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Monitoring Pembayaran</h1>
                    <p class="text-sm text-warm-600 mt-1">Seluruh data pembayaran transaksi BookSoed</p>
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-xs font-semibold text-warm-500">Filter:</span>
                @foreach (['', 'PAID', 'PENDING', 'FAILED'] as $s)
                    <a href="{{ route('admin.payments.index', $s ? ['status' => $s] : []) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all
                              {{ request('status') === $s || (!request('status') && $s === '')
                                 ? 'bg-brand-700 text-white border-brand-700'
                                 : 'bg-white text-warm-700 border-warm-200 hover:bg-warm-100' }}">
                        @php
                            $labels = ['' => 'Semua', 'PAID' => 'Lunas', 'PENDING' => 'Menunggu', 'FAILED' => 'Gagal'];
                        @endphp
                        {{ $labels[$s] }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                @if ($payments->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-3xl mb-3">💳</div>
                        <p class="text-sm text-warm-600">Belum ada data pembayaran.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-warm-50 border-b border-warm-200 text-xs font-semibold text-warm-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Order</th>
                                    <th class="px-4 py-3.5">Buyer</th>
                                    <th class="px-4 py-3.5">Metode</th>
                                    <th class="px-4 py-3.5 text-right">Jumlah</th>
                                    <th class="px-4 py-3.5 text-center">Status</th>
                                    <th class="px-4 py-3.5">Ref. Transaksi</th>
                                    <th class="px-4 py-3.5 text-right">Waktu Bayar</th>
                                    <th class="px-5 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-warm-100 text-warm-900">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-warm-50 transition">
                                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-warm-700">
                                            #BS-{{ str_pad($payment->order->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-warm-800">{{ $payment->order->buyer->name }}</td>
                                        <td class="px-4 py-3.5 text-xs text-warm-700">
                                            {{ $payment->payment_method }}
                                            @if ($payment->ewallet_provider)
                                                <span class="text-warm-500">({{ $payment->ewallet_provider }})</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-right text-xs font-bold text-brand-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            @include('payments._status-badge', ['status' => $payment->payment_status])
                                        </td>
                                        <td class="px-4 py-3.5 text-xs font-mono text-warm-500 max-w-[140px] truncate">
                                            {{ $payment->transaction_reference ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right text-xs text-warm-500">
                                            {{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : '—' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <a href="{{ route('admin.payments.show', $payment) }}"
                                               class="px-2.5 py-1 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-semibold rounded-lg transition-all hover:-translate-y-0.5 inline-block">
                                                Detail →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-warm-100">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
