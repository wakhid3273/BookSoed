<x-crm-layout>
    <x-slot name="title">Marketplace Buku Unsoed</x-slot>

    {{-- ══════════════════════════════════════════════════════════════
         HERO SECTION
    ══════════════════════════════════════════════════════════════ --}}
    <div style="text-align: center; margin: 3rem 0 4rem;" class="fade-up">
        <h1 class="nouveau-heading" style="font-size: 3.5rem; margin-bottom: 1rem;">
            BookSoed
        </h1>
        <p class="text-water" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem; font-family: 'Cormorant Garamond', serif;">
            Platform marketplace buku bekas eksklusif untuk Civitas Akademika Universitas Jenderal Soedirman.
            Temukan buku kuliah, novel, dan literatur lainnya dengan mudah.
        </p>

        @guest
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('register') }}" class="lg-btn lg-btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="lg-btn lg-btn-ghost" style="padding: 0.8rem 2rem; font-size: 1rem;">
                    Masuk
                </a>
            </div>
        @endguest
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         BOOK GRID
    ══════════════════════════════════════════════════════════════ --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
        <h2 class="nouveau-subheading" style="font-size: 1.8rem; margin: 0;">Buku Tersedia</h2>
        <span class="text-muted" style="font-size: 0.85rem;">{{ $books->total() }} buku</span>
    </div>

    @if($books->isEmpty())
        <div class="lg-card" style="padding: 4rem 2rem; text-align: center;">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" style="margin: 0 auto 1.5rem; opacity: 0.3;">
                <circle cx="30" cy="30" r="28" stroke="#DDB911" stroke-width="1.5" stroke-dasharray="4 4" fill="none"/>
                <path d="M20 25h20M20 35h15" stroke="#5C9550" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <h3 class="nouveau-subheading" style="margin: 0 0 0.5rem; color: var(--petal);">Belum ada buku</h3>
            <p class="text-muted" style="font-size: 0.9rem;">Saat ini belum ada buku yang tersedia untuk dijual.</p>
        </div>
    @else
        <div style="
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        ">
            @foreach($books as $book)
                <div class="lg-card fade-up" style="animation-delay: {{ $loop->index * 50 }}ms; overflow:visible;">
                    
                    {{-- Cover & Badges --}}
                    <div style="position:relative;">
                        @if($book->photo_url)
                            <img src="{{ $book->photo_url }}" alt="{{ $book->title }}" class="book-cover" loading="lazy">
                        @else
                            <div class="book-cover-placeholder">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" style="opacity:0.5">
                                    <rect x="4" y="2" width="24" height="28" rx="2" stroke="#DDB911" stroke-width="1.2" fill="none"/>
                                    <path d="M9 10h14M9 15h10M9 20h7" stroke="#5C9550" stroke-width="1" stroke-linecap="round"/>
                                </svg>
                                <span style="font-size:0.7rem; color: var(--text-muted); text-align:center; padding: 0 0.5rem; line-height: 1.3;">
                                    {{ Str::limit($book->title, 25) }}
                                </span>
                            </div>
                        @endif

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
                            <span class="lg-pill {{ $condClass }}" style="font-size:0.65rem; padding: 0.15rem 0.6rem;">
                                {{ $condLabel }}
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div style="padding: 1rem; position:relative; z-index: 2;">
                        <h3 style="
                            margin: 0 0 0.3rem;
                            font-size: 0.95rem; font-weight: 500;
                            color: var(--text-primary);
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                            line-height: 1.4;
                        " title="{{ $book->title }}">{{ $book->title }}</h3>

                        <p style="margin: 0 0 0.5rem; font-size: 0.78rem; color: var(--text-muted);">
                            {{ $book->author }}
                        </p>

                        @if($book->category)
                            <span class="lg-pill lg-pill-muted" style="font-size: 0.65rem; margin-bottom: 0.75rem; display:inline-block;">
                                {{ $book->category->name }}
                            </span>
                        @endif

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.5rem;">
                            <span style="
                                font-family: 'Cormorant Garamond', serif;
                                font-size: 1.2rem; font-weight: 700;
                                color: var(--petal);
                            ">
                                Rp{{ number_format($book->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div style="margin-top: 1rem;">
                            @auth
                                {{-- Jika login, bisa wishlist atau order --}}
                                <form action="{{ route('crm.wishlist.toggle', $book) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="lg-btn lg-btn-plum" style="width: 100%; justify-content: center; padding: 0.4rem;">
                                        + Wishlist
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="lg-btn lg-btn-ghost" style="width: 100%; justify-content: center; padding: 0.4rem; font-size: 0.75rem;">
                                    Masuk untuk beli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($books->hasPages())
            <div style="display:flex; justify-content:center;">
                {{ $books->links() }}
            </div>
        @endif
    @endif

</x-crm-layout>
