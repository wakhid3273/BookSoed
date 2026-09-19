<x-app-layout>
    <div class="py-8 page-enter">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Pesanan Masuk</h1>
                    <p class="text-sm text-warm-800 mt-1">Daftar pesanan dari pembeli yang memesan buku dari toko/listing milikmu</p>
                </div>
                <a href="{{ route('books.my-listings') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-warm-200 hover:bg-warm-50 text-warm-800 text-xs font-semibold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5">
                    📚 Kelola Listing Saya
                </a>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white rounded-2xl border border-warm-200 p-12 sm:p-16 text-center space-y-4 shadow-xs">
                    <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center mx-auto text-3xl shadow-xs">
                        📬
                    </div>
                    <h3 class="font-bold text-warm-900 text-lg">Belum Ada Pesanan Masuk</h3>
                    <p class="text-sm text-warm-600 max-w-md mx-auto leading-relaxed">
                        Pesanan dari pembeli akan muncul di sini setelah seseorang membeli buku Anda.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('books.my-listings') }}"
                           class="btn-primary">
                            📚 Kelola Listing
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="bg-white rounded-2xl border border-warm-200 p-5 sm:p-6 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-1 hover:border-brand-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-warm-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-sm font-bold text-warm-900 bg-warm-100 px-2.5 py-1 rounded-lg">
                                        #BS-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="text-xs text-warm-500">
                                        {{ $order->order_date->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-warm-500 font-medium">Status Order:</span>
                                    @include('orders._status-badge', ['status' => $order->status])
                                </div>
                            </div>

                            <div class="py-4 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Buyer & Book Details -->
                                <div class="md:col-span-8 space-y-3">
                                    <div class="flex items-center gap-2 text-xs font-semibold text-warm-900">
                                        <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-[11px]">
                                            👤
                                        </div>
                                        <span>Pembeli: {{ $order->buyer->name }}</span>
                                    </div>

                                    <div class="space-y-2 pl-1">
                                        @foreach ($order->items as $item)
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-12 rounded bg-warm-100 border border-warm-200 overflow-hidden shrink-0 flex items-center justify-center text-warm-400">
                                                    @if ($item->book && $item->book->image_path)
                                                        <img src="{{ asset('storage/' . $item->book->image_path) }}" alt="{{ $item->book->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-base">📚</span>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="font-semibold text-warm-900 text-sm truncate">
                                                        {{ $item->book->title ?? '(Buku telah dihapus)' }}
                                                    </p>
                                                    <p class="text-xs text-warm-500">
                                                        @if ($item->book)
                                                            {{ $item->book->author }} ·
                                                        @endif
                                                        <span class="font-medium text-warm-800">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Payment & Total -->
                                <div class="md:col-span-4 border-t md:border-t-0 md:border-l border-warm-100 pt-3 md:pt-0 md:pl-6 flex flex-col justify-between h-full space-y-3">
                                    <div>
                                        <div class="text-xs text-warm-500 mb-1">Status Pembayaran</div>
                                        @if ($order->payment)
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="font-medium text-warm-800">
                                                    {{ $order->payment->payment_method }}
                                                    @if ($order->payment->ewallet_provider)
                                                        ({{ $order->payment->ewallet_provider }})
                                                    @endif
                                                </span>
                                                @include('payments._status-badge', ['status' => $order->payment->payment_status])
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-warm-100 text-warm-700">
                                                Belum dibayar
                                            </span>
                                        @endif
                                    </div>

                                    <div class="pt-2">
                                        <div class="text-xs text-warm-500">Total Order</div>
                                        <div class="font-bold text-lg text-brand-700">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-warm-100 pt-4 flex justify-between items-center">
                                <div class="text-xs text-warm-500">
                                    {{ $order->items->count() }} item buku
                                </div>
                                <a href="{{ route('seller.orders.show', $order) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5">
                                    <span>Lihat Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
