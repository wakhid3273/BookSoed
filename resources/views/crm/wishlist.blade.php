<x-crm-layout>
    <x-slot name="title">Wishlist Saya</x-slot>

    {{-- ══════════════════════════════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════════════════════════════ --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 class="nouveau-heading" id="wishlist-heading">Wishlist Saya</h1>
            <p class="text-muted" style="margin: 0.5rem 0 0; font-size: 0.85rem;">
                Buku yang ingin kamu beli nanti — {{ $wishlists->total() }} buku tersimpan
            </p>
        </div>
        <a href="{{ route('crm.dashboard') }}" class="lg-btn lg-btn-ghost" id="btn-back-dashboard">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M9 11L5 7l4-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         WISHLIST GRID
    ══════════════════════════════════════════════════════════════ --}}
    @if($wishlists->isEmpty())
        {{-- Empty State --}}
        <div class="lg-card" style="padding: 4rem 2rem; text-align: center;" id="wishlist-empty">
            {{-- Art Nouveau decorative flower SVG --}}
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" style="margin: 0 auto 1.5rem; display:block; opacity:0.4;">
                <circle cx="40" cy="40" r="6" fill="#DDB911"/>
                <ellipse cx="40" cy="22" rx="6" ry="12" fill="#F6D83A" fill-opacity="0.7"/>
                <ellipse cx="40" cy="58" rx="6" ry="12" fill="#F6D83A" fill-opacity="0.7"/>
                <ellipse cx="22" cy="40" rx="12" ry="6" fill="#5C9550" fill-opacity="0.6"/>
                <ellipse cx="58" cy="40" rx="12" ry="6" fill="#5C9550" fill-opacity="0.6"/>
                <ellipse cx="28" cy="28" rx="6" ry="11" fill="#86C3C9" fill-opacity="0.5" transform="rotate(45 28 28)"/>
                <ellipse cx="52" cy="52" rx="6" ry="11" fill="#86C3C9" fill-opacity="0.5" transform="rotate(45 52 52)"/>
                <ellipse cx="52" cy="28" rx="6" ry="11" fill="#90456e" fill-opacity="0.45" transform="rotate(-45 52 28)"/>
                <ellipse cx="28" cy="52" rx="6" ry="11" fill="#90456e" fill-opacity="0.45" transform="rotate(-45 28 52)"/>
            </svg>
            <h2 class="nouveau-subheading" style="margin: 0 0 0.5rem;">Wishlist Masih Kosong</h2>
            <p class="text-muted" style="font-size:0.85rem; max-width:320px; margin: 0 auto 1.5rem;">
                Temukan buku yang kamu inginkan dan tambahkan ke wishlist.
            </p>
            <a href="/" class="lg-btn lg-btn-primary" id="btn-browse-books">
                Jelajahi Buku
            </a>
        </div>

    @else
        {{-- Book Grid --}}
        <div id="wishlist-grid" style="
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        ">
            @foreach($wishlists as $item)
                @php $book = $item->book; @endphp
                <div class="lg-card" id="wishlist-book-{{ $book->book_id }}" style="overflow:visible;">
                    <div style="position:relative;">

                        {{-- Book Cover / Placeholder --}}
                        @if($book->photo_url)
                            <img src="{{ $book->photo_url }}"
                                 alt="{{ $book->title }}"
                                 class="book-cover"
                                 loading="lazy"
                                 id="book-cover-{{ $book->book_id }}">
                        @else
                            <div class="book-cover-placeholder">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" style="opacity:0.5">
                                    <rect x="4" y="2" width="24" height="28" rx="2" stroke="#DDB911" stroke-width="1.2" fill="none"/>
                                    <path d="M9 10h14M9 15h10M9 20h7" stroke="#5C9550" stroke-width="1" stroke-linecap="round"/>
                                </svg>
                                <span style="font-size:0.65rem; color: var(--text-muted); text-align:center; padding: 0 0.5rem; line-height: 1.3;">
                                    {{ Str::limit($book->title, 30) }}
                                </span>
                            </div>
                        @endif

                        {{-- Remove from Wishlist Button --}}
                        <form method="POST"
                              action="{{ route('crm.wishlist.toggle', $book) }}"
                              style="position:absolute; top:8px; right:8px;"
                              id="form-remove-wishlist-{{ $book->book_id }}">
                            @csrf
                            <button type="submit"
                                    class="wishlist-heart active"
                                    title="Hapus dari Wishlist"
                                    id="btn-remove-{{ $book->book_id }}">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M7 12S1 8.5 1 4.5a3 3 0 016 0 3 3 0 016 0C13 8.5 7 12 7 12z"
                                          stroke="#c98ab8" stroke-width="1.2"
                                          fill="rgba(144,69,110,0.6)"/>
                                </svg>
                            </button>
                        </form>

                        {{-- Condition Badge --}}
                        <div style="position:absolute; bottom: 8px; left: 8px;">
                            @php
                                $condClass = match($book->condition) {
                                    'like_new' => 'lg-pill-leaf',
                                    'good'     => 'lg-pill-water',
                                    'new'      => 'lg-pill-petal',
                                    default    => 'lg-pill-muted',
                                };
                                $condLabel = match($book->condition) {
                                    'like_new' => 'Seperti Baru',
                                    'good'     => 'Baik',
                                    'fair'     => 'Cukup',
                                    'poor'     => 'Perlu Perbaikan',
                                    'new'      => 'Baru',
                                    default    => $book->condition,
                                };
                            @endphp
                            <span class="lg-pill {{ $condClass }}" style="font-size:0.6rem; padding: 0.1rem 0.5rem;">
                                {{ $condLabel }}
                            </span>
                        </div>
                    </div>

                    {{-- Book Info --}}
                    <div style="padding: 0.85rem; position:relative; z-index: 2;">
                        <h3 style="
                            margin: 0 0 0.2rem;
                            font-size: 0.82rem; font-weight: 500;
                            color: var(--text-primary);
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                            line-height: 1.35;
                        " title="{{ $book->title }}">{{ $book->title }}</h3>

                        <p style="margin: 0 0 0.25rem; font-size: 0.72rem; color: var(--text-muted);">
                            {{ $book->author }}
                        </p>

                        @if($book->category)
                            <span class="lg-pill lg-pill-muted" style="font-size: 0.6rem; margin-bottom: 0.5rem; display:inline-block;">
                                {{ $book->category->name }}
                            </span>
                        @endif

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.5rem;">
                            <span style="
                                font-family: 'Cormorant Garamond', serif;
                                font-size: 1rem; font-weight: 700;
                                color: var(--petal);
                            ">
                                Rp{{ number_format($book->price, 0, ',', '.') }}
                            </span>

                            @if($book->status === 'available')
                                <a href="#"
                                   class="lg-btn lg-btn-primary"
                                   style="padding: 0.25rem 0.7rem; font-size: 0.7rem;"
                                   id="btn-buy-{{ $book->book_id }}">
                                    Beli
                                </a>
                            @else
                                <span class="lg-pill lg-pill-plum" style="font-size:0.65rem;">Tidak Tersedia</span>
                            @endif
                        </div>

                        <p style="margin: 0.5rem 0 0; font-size: 0.68rem; color: rgba(138,171,140,0.5);">
                            Oleh: {{ $book->seller?->name ?? 'Anonim' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($wishlists->hasPages())
            <div class="crm-pagination" style="display:flex; justify-content:center; gap:0.25rem;" id="wishlist-pagination">
                {{ $wishlists->links() }}
            </div>
        @endif
    @endif

</x-crm-layout>
