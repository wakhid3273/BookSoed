<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Monitoring Order</h1>
                    <p class="text-sm text-warm-600 mt-1">Seluruh transaksi order di marketplace BookSoed</p>
                </div>
            </div>

            {{-- Filter Status --}}
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-2 items-center">
                <span class="text-xs font-semibold text-warm-500">Filter:</span>
                @foreach (['', 'PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'] as $s)
                    <a href="{{ route('admin.orders.index', $s ? ['status' => $s] : []) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all
                              {{ request('status') === $s || (!request('status') && $s === '')
                                 ? 'bg-brand-700 text-white border-brand-700'
                                 : 'bg-white text-warm-700 border-warm-200 hover:bg-warm-100' }}">
                        {{ $s ?: 'Semua' }}
                    </a>
                @endforeach
            </form>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                @if ($orders->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-3xl mb-3">📦</div>
                        <p class="text-sm text-warm-600">Belum ada order.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-warm-50 border-b border-warm-200 text-xs font-semibold text-warm-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Order</th>
                                    <th class="px-4 py-3.5">Buyer</th>
                                    <th class="px-4 py-3.5">Seller</th>
                                    <th class="px-4 py-3.5 text-center">Item</th>
                                    <th class="px-4 py-3.5 text-right">Subtotal</th>
                                    <th class="px-4 py-3.5 text-right">Svc Fee</th>
                                    <th class="px-4 py-3.5 text-right">Total</th>
                                    <th class="px-4 py-3.5 text-center">Status Order</th>
                                    <th class="px-4 py-3.5 text-center">Pembayaran</th>
                                    <th class="px-5 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-warm-100 text-warm-900">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-warm-50 transition">
                                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-warm-700">
                                            #BS-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                            <div class="text-[10px] text-warm-400 font-normal">{{ $order->order_date->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-warm-800">{{ $order->buyer->name }}</td>
                                        <td class="px-4 py-3.5 text-xs text-warm-800">{{ $order->seller->name }}</td>
                                        <td class="px-4 py-3.5 text-center text-xs font-mono text-warm-700">{{ $order->items_count ?? $order->items->count() }}</td>
                                        <td class="px-4 py-3.5 text-right text-xs text-warm-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-right text-xs text-warm-600">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-right text-xs font-bold text-brand-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            @include('orders._status-badge', ['status' => $order->status])
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            @if ($order->payment)
                                                @include('payments._status-badge', ['status' => $order->payment->payment_status])
                                            @else
                                                <span class="text-[11px] text-warm-400 font-medium">—</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <a href="{{ route('admin.orders.show', $order) }}"
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
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
