<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900" id="wishlist-heading">Wishlist Saya</h1>
                    <p class="text-sm text-warm-700 mt-1">
                        Buku yang ingin kamu beli nanti —
                        <span class="font-semibold text-brand-700">{{ $wishlists->total() }}</span> buku tersimpan
                    </p>
                </div>
                <a href="{{ route('crm.dashboard') }}"
                   class="btn-secondary text-sm px-4 py-2 shrink-0"
                   id="btn-back-dashboard">
                    ← Kembali ke Profil
                </a>
            </div>

            {{-- ── FLASH MESSAGE ───────────────────────────────────────── --}}
            @if(session('wishlist_status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2"
                     id="wishlist-flash" role="alert">
                    ✓ {{ session('wishlist_status') }}
                </div>
            @endif

            {{-- ── EMPTY STATE ─────────────────────────────────────────── --}}
            @if($wishlists->isEmpty())
                <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-16 text-center"
                     id="wishlist-empty">
                    <div class="text-6xl mb-4 opacity-30">❤️</div>
                    <h2 class="font-serif text-xl font-bold text-warm-900 mb-2">Wishlist Masih Kosong</h2>
                    <p class="text-sm text-warm-600 max-w-xs mx-auto mb-6">
                        Temukan buku yang kamu inginkan dan tambahkan ke wishlist.
                    </p>
                    <a href="{{ route('books.index') }}" class="btn-primary" id="btn-browse-books">
                        📖 Jelajahi Buku
                    </a>
                </div>

            @else
                {{-- ── WISHLIST GRID ────────────────────────────────────── --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4"
                     id="wishlist-grid">
                    @foreach($wishlists as $item)
                        @php $book = $item->book; @endphp
                        <div class="book-card group" id="wishlist-book-{{ $book->book_id }}">

                            {{-- Cover + Overlay Buttons --}}
                            <div class="book-card-image relative aspect-[3/4] bg-warm-100">
                                @if($book->photo_url)
                                    <img src="{{ $book->photo_url }}"
                                         alt="{{ $book->title }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy"
                                         id="book-cover-{{ $book->book_id }}">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center gap-2
                                                bg-gradient-to-br from-brand-50 to-warm-100 p-3">
                                        <span class="text-3xl opacity-40">📚</span>
                                        <span class="text-xs text-warm-500 text-center leading-snug line-clamp-3">
                                            {{ $book->title }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Remove Wishlist Button --}}
                                <form method="POST"
                                      action="{{ route('crm.wishlist.toggle', $book) }}"
                                      class="absolute top-2 right-2"
                                      id="form-remove-wishlist-{{ $book->book_id }}">
                                    @csrf
                                    <button type="submit"
                                            title="Hapus dari Wishlist"
                                            id="btn-remove-{{ $book->book_id }}"
                                            class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm border border-warm-200
                                                   flex items-center justify-center shadow-sm text-sm
                                                   hover:bg-red-50 hover:border-red-200 transition">
                                        ❤️
                                    </button>
                                </form>

                                {{-- Condition Badge --}}
                                <div class="absolute bottom-2 left-2">
                                    @php
                                        $condClass = match($book->condition) {
                                            'LIKE_NEW', 'like_new' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'GOOD', 'good'         => 'bg-brand-50 text-brand-700 border-brand-200',
                                            'NEW', 'new'           => 'bg-blue-100 text-blue-800 border-blue-200',
                                            default                => 'bg-warm-100 text-warm-700 border-warm-200',
                                        };
                                        $condLabel = match(strtolower($book->condition)) {
                                            'like_new' => 'Seperti Baru',
                                            'good'     => 'Baik',
                                            'fair'     => 'Cukup',
                                            'poor'     => 'Perlu Perbaikan',
                                            'new'      => 'Baru',
                                            default    => $book->condition,
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                                 text-[10px] font-semibold border {{ $condClass }}">
                                        {{ $condLabel }}
                                    </span>
                                </div>
                            </div>

                            {{-- Book Info --}}
                            <div class="p-3 flex flex-col flex-1">
                                <h3 class="text-xs font-semibold text-warm-900 line-clamp-2 leading-snug mb-1"
                                    title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h3>

                                <p class="text-[11px] text-warm-500 mb-1.5 truncate">
                                    {{ $book->author }}
                                </p>

                                @if($book->category)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                                 text-[10px] font-medium bg-warm-100 text-warm-600 border border-warm-200
                                                 mb-2 self-start">
                                        {{ $book->category->name }}
                                    </span>
                                @endif

                                <div class="mt-auto flex items-center justify-between gap-1">
                                    <span class="font-serif text-sm font-bold text-warm-900">
                                        Rp{{ number_format($book->price, 0, ',', '.') }}
                                    </span>

                                    @if(strtolower($book->status) === 'available')
                                        <a href="{{ route('books.show', $book) }}"
                                           class="btn-sm-primary text-[10px] px-2 py-1"
                                           id="btn-buy-{{ $book->book_id }}">
                                            Beli
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full
                                                     text-[10px] font-semibold bg-warm-100 text-warm-500 border border-warm-200">
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </div>

                                <p class="text-[10px] text-warm-400 mt-1.5 truncate">
                                    Oleh: {{ $book->seller?->name ?? 'Anonim' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── PAGINATION ──────────────────────────────────────── --}}
                @if($wishlists->hasPages())
                    <div class="flex justify-center" id="wishlist-pagination">
                        {{ $wishlists->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-dismiss flash alert
        const flashAlert = document.getElementById('wishlist-flash');
        if (flashAlert) {
            setTimeout(() => {
                flashAlert.style.transition = 'opacity 0.4s';
                flashAlert.style.opacity = '0';
                setTimeout(() => flashAlert.remove(), 400);
            }, 4000);
        }
    </script>
    @endpush
</x-app-layout>
