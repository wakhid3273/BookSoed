<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between">
                <x-back-button :href="route('admin.books.index')" label="Kembali ke Listing" />
            </div>

            <!-- Book Header Card -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                <div class="p-6 flex flex-col sm:flex-row gap-5">
                    <!-- Cover -->
                    <div class="w-28 h-36 rounded-xl bg-warm-100 border border-warm-200 overflow-hidden shrink-0 flex items-center justify-center text-warm-400">
                        @if ($book->photo_path)
                            <img src="{{ asset('storage/' . $book->photo_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">📚</span>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 space-y-3">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <h1 class="font-serif text-2xl font-bold text-warm-900">{{ $book->title }}</h1>
                            @php
                                $statusClass = match($book->status) {
                                    'AVAILABLE' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'RESERVED'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'SOLD'      => 'bg-warm-100 text-warm-700 border-warm-200',
                                    default     => 'bg-warm-100 text-warm-700 border-warm-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                {{ \App\Models\Book::statuses()[$book->status] ?? $book->status }}
                            </span>
                        </div>
                        <p class="text-sm text-warm-600">oleh <span class="font-semibold text-warm-800">{{ $book->author }}</span></p>

                        <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                            <div>
                                <span class="text-warm-400 block">Kategori</span>
                                <span class="font-semibold text-warm-800">{{ $book->category->name }}</span>
                            </div>
                            <div>
                                <span class="text-warm-400 block">Kondisi</span>
                                <span class="font-semibold text-warm-800">{{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}</span>
                            </div>
                            <div>
                                <span class="text-warm-400 block">Harga</span>
                                <span class="font-bold text-brand-700 text-sm">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                            </div>
                            @if ($book->isbn)
                                <div>
                                    <span class="text-warm-400 block">ISBN</span>
                                    <span class="font-mono text-warm-800 text-xs">{{ $book->isbn }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-warm-50/70 border-t border-warm-100 grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-warm-400 block mb-0.5">Seller</span>
                        <span class="font-semibold text-warm-900">{{ $book->seller->name }}</span>
                        <span class="text-warm-500 text-[11px] block">{{ $book->seller->email }}</span>
                    </div>
                    <div>
                        <span class="text-warm-400 block mb-0.5">Tanggal Listing</span>
                        <span class="font-semibold text-warm-900">{{ $book->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
