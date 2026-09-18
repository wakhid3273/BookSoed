<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order Saya</h2>
            <a href="{{ route('books.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Marketplace</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
                    <p class="text-5xl mb-4">📦</p>
                    <p class="text-lg">Kamu belum memiliki Order.</p>
                    <a href="{{ route('books.index') }}"
                       class="mt-4 inline-block text-indigo-600 hover:underline">Mulai belanja buku →</a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-5 py-3 text-left text-gray-500 font-medium">#Order</th>
                                <th class="px-4 py-3 text-left text-gray-500 font-medium">Seller</th>
                                <th class="px-4 py-3 text-left text-gray-500 font-medium">Tanggal</th>
                                <th class="px-4 py-3 text-center text-gray-500 font-medium">Item</th>
                                <th class="px-4 py-3 text-right text-gray-500 font-medium">Total</th>
                                <th class="px-4 py-3 text-center text-gray-500 font-medium">Status</th>
                                <th class="px-4 py-3 text-center text-gray-500 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 font-mono text-gray-500">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-4 text-gray-700">{{ $order->seller->name }}</td>
                                    <td class="px-4 py-4 text-gray-500">{{ $order->order_date->format('d M Y') }}</td>
                                    <td class="px-4 py-4 text-center text-gray-600">{{ $order->items->count() }}</td>
                                    <td class="px-4 py-4 text-right font-semibold text-gray-800">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @include('orders._status-badge', ['status' => $order->status])
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('orders.show', $order) }}"
                                           class="text-xs px-3 py-1 bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-5 py-4 border-t">{{ $orders->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
