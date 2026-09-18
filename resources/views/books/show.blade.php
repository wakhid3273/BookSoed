<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Navigation Back & Breadcrumb --}}
            <div class="flex items-center gap-3">
                <x-back-button :href="route('books.index')" label="Kembali ke Marketplace" />
                <span class="text-warm-300">|</span>
                <span class="text-xs text-warm-900 font-medium truncate max-w-xs">{{ $book->title }}</span>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12">

                    {{-- Left Image Panel --}}
                    <div class="md:col-span-5 bg-warm-100 p-8 flex items-center justify-center border-b md:border-b-0 md:border-r border-warm-200 min-h-[320px]">
                        @if ($book->photo_path)
                            <img src="{{ Storage::url($book->photo_path) }}"
                                 alt="{{ $book->title }}"
                                 class="max-h-80 w-auto object-contain rounded-xl shadow-md">
                        @else
                            <div class="text-center text-warm-400 space-y-2">
                                <span class="text-7xl">📚</span>
                                <p class="text-xs font-mono uppercase tracking-wider">BookSoed Cover</p>
                            </div>
                        @endif
                    </div>

                    {{-- Right Info Panel --}}
                    <div class="md:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <span class="inline-block text-xs font-semibold uppercase tracking-wider text-brand-700 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-full mb-2">
                                        {{ $book->category->name }}
                                    </span>
                                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-warm-900 leading-tight">
                                        {{ $book->title }}
                                    </h1>
                                    <p class="text-sm text-warm-700 mt-1">
                                        Penulis: <strong class="text-warm-900">{{ $book->author }}</strong>
                                    </p>
                                </div>
                                <div>
                                    @include('books._status-badge', ['status' => $book->status])
                                </div>
                            </div>

                            {{-- Book Metadata Table --}}
                            <div class="grid grid-cols-2 gap-4 py-4 border-y border-warm-200 text-sm">
                                <div>
                                    <span class="text-xs text-warm-500 block">Kondisi Buku</span>
                                    <span class="font-semibold text-warm-900">
                                        {{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-xs text-warm-500 block">Penjual (Seller)</span>
                                    <span class="font-semibold text-warm-900">
                                        👤 {{ $book->seller->name }}
                                    </span>
                                </div>
                                @if ($book->isbn)
                                    <div class="col-span-2 sm:col-span-1">
                                        <span class="text-xs text-warm-500 block">ISBN</span>
                                        <span class="font-mono text-xs text-warm-800">
                                            {{ $book->isbn }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Price Tag --}}
                            <div class="pt-2">
                                <span class="text-xs text-warm-500 block">Harga Buku</span>
                                <div class="text-3xl font-bold text-brand-700">
                                    Rp {{ number_format($book->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-warm-200 flex flex-wrap items-center gap-3">
                            @if ($book->status === 'AVAILABLE')
                                @auth
                                    @if (auth()->id() !== $book->user_id)
                                        <form action="{{ route('orders.store') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="book_ids[]" value="{{ $book->id }}">
                                            <button type="submit"
                                                    class="w-full px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-150"
                                                    onclick="return confirm('Pesan buku ini seharga Rp {{ number_format($book->price, 0, \',\', \'.\') }}?')">
                                                🛒 Pesan Sekarang
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-4 py-2.5 bg-warm-100 text-warm-600 text-sm font-medium rounded-xl border border-warm-200">
                                            ℹ️ Ini adalah listing milikmu
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="flex-1 text-center px-6 py-3 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow transition">
                                        🔒 Login untuk Memesan
                                    </a>
                                @endauth
                            @else
                                <span class="px-5 py-2.5 bg-warm-100 text-warm-500 text-sm font-medium rounded-xl border border-warm-200">
                                    Buku Tidak Tersedia Saat Ini
                                </span>
                            @endif

                            @auth
                                @if (auth()->id() === $book->user_id && $book->status !== 'SOLD')
                                    <a href="{{ route('books.edit', $book) }}"
                                       class="px-4 py-3 bg-amber-50 text-amber-800 border border-amber-200 text-sm font-semibold rounded-xl hover:bg-amber-100 transition">
                                        ✏️ Edit Listing
                                    </a>
                                @endif
                            @endauth
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
