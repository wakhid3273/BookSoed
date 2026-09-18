<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Pesanan Masuk — Seller</h1>
                    <p class="text-sm text-warm-800 mt-1">Daftar pesanan dari pembeli yang memesan buku dari toko/listing milikmu</p>
                </div>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white rounded-2xl border border-warm-200 p-16 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-warm-100 text-warm-400 flex items-center justify-center mx-auto text-3xl">
                        📬
                    </div>
                    <h3 class="font-bold text-warm-900 text-lg">Belum Ada Pesanan Masuk</h3>
                    <p class="text-sm text-warm-800 max-w-md mx-auto">
                        Pesanan dari pembeli akan muncul di sini ketika ada mahasiswa yang memesan buku dari listing kamu.
                    </p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-warm-100/70 border-b border-warm-200 text-xs font-semibold text-warm-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">#Order</th>
                                    <th class="px-4 py-3.5">Pembeli (Buyer)</th>
                                    <th class="px-4 py-3.5">Tanggal</th>
                                    <th class="px-4 py-3.5">Buku Dipesan</th>
                                    <th class="px-4 py-3.5 text-right">Total Tagihan</th>
                                    <th class="px-4 py-3.5 text-center">Status</th>
                                    <th class="px-6 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-warm-100 text-warm-900">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-warm-50/60 transition">
                                        <td class="px-6 py-4 font-mono text-xs font-semibold text-warm-700">
                                            #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-4 py-4 text-xs font-medium text-warm-900">
                                            👤 {{ $order->buyer->name }}
                                        </td>
                                        <td class="px-4 py-4 text-xs text-warm-600">
                                            {{ $order->order_date->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4 text-xs text-warm-700">
                                            @foreach ($order->items as $item)
                                                <p class="font-medium truncate max-w-xs">{{ $item->book->title ?? '-' }}</p>
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-brand-700">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            @include('orders._status-badge', ['status' => $order->status])
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('seller.orders.show', $order) }}"
                                               class="px-3 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-semibold rounded-lg transition">
                                                Kelola Order →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
