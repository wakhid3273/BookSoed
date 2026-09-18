<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Order Saya</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            {{-- Info Order --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Informasi Order</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $order->order_date->format('d M Y, H:i') }}</p>
                    </div>
                    @include('orders._status-badge', ['status' => $order->status])
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Seller</p>
                        <p class="font-medium text-gray-700">{{ $order->seller->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Buyer</p>
                        <p class="font-medium text-gray-700">{{ $order->buyer->name }}</p>
                    </div>
                </div>
            </div>

            {{-- OrderItems --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-gray-800">Daftar Buku</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-5 py-3 text-left text-gray-500 font-medium">Buku</th>
                            <th class="px-4 py-3 text-right text-gray-500 font-medium">Harga Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">{{ $item->book->title ?? '(Buku telah dihapus)' }}</p>
                                    @if ($item->book)
                                        <p class="text-xs text-gray-400">{{ $item->book->author }} · {{ $item->book->category->name ?? '' }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-gray-700">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Ringkasan total --}}
                <div class="px-6 py-4 border-t bg-gray-50 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Service Fee</span>
                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 text-base border-t pt-2 mt-2">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-gray-400 pt-1">* Delivery fee (jika ada) akan ditentukan oleh SCM pada proses fulfillment.</p>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex gap-3">
                @if ($order->status === 'PENDING')
                    <form action="{{ route('orders.cancel', $order) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan order ini? Buku akan dikembalikan ke listing.')">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2 bg-red-100 text-red-600 text-sm font-medium rounded-md hover:bg-red-200 transition">
                            Batalkan Order
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
