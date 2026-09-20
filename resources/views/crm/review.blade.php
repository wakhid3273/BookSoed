<x-crm-layout>
    <x-slot name="title">Beri Ulasan</x-slot>

    <div style="max-width: 640px; margin: 0 auto;">

        {{-- ══════════════════════════════════════════════════════════
             HEADER
        ══════════════════════════════════════════════════════════ --}}
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('crm.dashboard') }}" class="lg-btn lg-btn-ghost" style="margin-bottom: 1rem; display:inline-flex;" id="btn-back-review">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M9 11L5 7l4-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="nouveau-heading" style="display:block; margin-bottom: 0.35rem;" id="review-heading">
                Beri Ulasan
            </h1>
            <p class="text-muted" style="font-size: 0.85rem; margin: 0;">
                Order #{{ $order->order_id }} &bull; Selesai {{ $order->completed_at?->format('d M Y') }}
            </p>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             ORDER SUMMARY CARD
        ══════════════════════════════════════════════════════════ --}}
        <div class="lg-card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;" id="review-order-summary">
            <div style="display:flex; align-items:center; gap:1rem;">
                {{-- Seller Avatar --}}
                <div style="
                    width:44px; height:44px; border-radius:50%; flex-shrink:0;
                    background: linear-gradient(135deg, #4B7416, #86C3C9);
                    display:flex; align-items:center; justify-content:center;
                    border: 1px solid rgba(134,195,201,0.35);
                    font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:700; color:#F6D83A;
                ">
                    {{ strtoupper(substr($order->seller?->name ?? 'S', 0, 1)) }}
                </div>

                <div style="flex:1; min-width:0;">
                    <div style="font-size:0.78rem; color: var(--text-muted); margin-bottom:0.15rem;">Penjual</div>
                    <div style="font-size:0.9rem; font-weight:500; color: var(--text-primary);">
                        {{ $order->seller?->name ?? 'Anonim' }}
                    </div>
                </div>

                {{-- Order total --}}
                <div style="text-align:right; flex-shrink:0;">
                    <div style="font-size:0.7rem; color:var(--text-muted);">Total</div>
                    <div style="
                        font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:700;
                        color: var(--petal);
                    ">
                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Books in order --}}
            @if($order->orderItems->isNotEmpty())
                <div style="margin-top:1rem; padding-top:1rem; border-top: 1px solid rgba(246,216,58,0.10);">
                    @foreach($order->orderItems as $item)
                        <div style="display:flex; gap:0.75rem; align-items:center; margin-bottom:0.5rem;">
                            <div style="
                                width:28px; height:36px; flex-shrink:0;
                                background: linear-gradient(135deg, rgba(75,116,22,0.5), rgba(12,20,16,0.9));
                                border-radius:3px; border:1px solid rgba(246,216,58,0.15);
                                display:flex; align-items:center; justify-content:center;
                            ">
                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                                    <rect x="1" y="0.5" width="8" height="9" rx="1" stroke="#DDB911" stroke-width="0.8" fill="none"/>
                                    <path d="M3 3.5h4M3 5.5h3" stroke="#5C9550" stroke-width="0.7"/>
                                </svg>
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:0.8rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $item->book?->title ?? '(buku tidak tersedia)' }}
                                </div>
                                <div style="font-size:0.7rem; color:var(--text-muted);">
                                    {{ $item->book?->author }}
                                </div>
                            </div>
                            <div style="font-size:0.78rem; color:var(--stamen); flex-shrink:0;">
                                Rp{{ number_format($item->price_at_order, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             REVIEW FORM
        ══════════════════════════════════════════════════════════ --}}
        <div class="lg-card" style="padding: 2rem;" id="review-form-card">

            {{-- Art Nouveau divider top --}}
            <div style="text-align:center; margin-bottom:1.75rem;">
                <svg width="120" height="16" viewBox="0 0 120 16" fill="none">
                    <path d="M0 8 Q20 2, 40 8 Q60 14, 80 8 Q100 2, 120 8" stroke="#DDB911" stroke-width="0.8" fill="none" stroke-opacity="0.5"/>
                    <circle cx="60" cy="8" r="3" fill="#F6D83A" fill-opacity="0.7"/>
                    <circle cx="30" cy="8" r="1.5" fill="#5C9550" fill-opacity="0.5"/>
                    <circle cx="90" cy="8" r="1.5" fill="#5C9550" fill-opacity="0.5"/>
                </svg>
            </div>

            <form method="POST"
                  action="{{ route('crm.review.store', $order) }}"
                  id="review-form">
                @csrf

                {{-- ── Star Rating ──────────────────────────── --}}
                <div style="margin-bottom: 1.75rem;" id="rating-section">
                    <label style="
                        display:block; margin-bottom:0.75rem;
                        font-size:0.8rem; text-transform:uppercase;
                        letter-spacing: 0.08em; color: var(--text-muted);
                    ">
                        Penilaian
                    </label>

                    <div class="star-rating" role="group" aria-label="Rating bintang" id="star-rating-group">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="star"
                                    data-value="{{ $i }}"
                                    id="star-{{ $i }}"
                                    aria-label="{{ $i }} bintang">
                                ★
                            </button>
                        @endfor
                    </div>

                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}">

                    {{-- Rating description text --}}
                    <p id="rating-desc" style="font-size:0.78rem; color:var(--text-muted); margin: 0.5rem 0 0; height: 1.2em;">
                        Klik bintang untuk memberi nilai
                    </p>

                    @error('rating')
                        <p style="font-size:0.78rem; color:#c98ab8; margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Comment ──────────────────────────────── --}}
                <div style="margin-bottom: 1.75rem;" id="comment-section">
                    <label for="review-comment" style="
                        display:block; margin-bottom:0.6rem;
                        font-size:0.8rem; text-transform:uppercase;
                        letter-spacing: 0.08em; color: var(--text-muted);
                    ">
                        Komentar <span style="font-weight:300; text-transform:none;">(opsional)</span>
                    </label>
                    <textarea name="comment"
                              id="review-comment"
                              class="lg-input"
                              rows="4"
                              maxlength="1000"
                              placeholder="Ceritakan pengalamanmu bertransaksi dengan penjual ini…"
                              style="resize: vertical; min-height: 100px;">{{ old('comment') }}</textarea>
                    <p style="font-size:0.7rem; color:var(--text-muted); margin: 0.35rem 0 0; text-align:right;" id="char-count">
                        0 / 1000
                    </p>
                    @error('comment')
                        <p style="font-size:0.78rem; color:#c98ab8; margin-top:0.4rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Submit ──────────────────────────────── --}}
                <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                    <a href="{{ route('crm.dashboard') }}"
                       class="lg-btn lg-btn-ghost"
                       id="btn-cancel-review">
                        Batal
                    </a>
                    <button type="submit"
                            class="lg-btn lg-btn-primary"
                            id="btn-submit-review"
                            disabled
                            style="opacity:0.45; cursor:not-allowed;">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M2 7l4 4L12 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>

        {{-- Art Nouveau bottom ornament --}}
        <div style="text-align:center; margin-top: 2rem; opacity: 0.25;">
            <svg width="160" height="24" viewBox="0 0 160 24" fill="none">
                <path d="M0 12 Q40 2, 80 12 Q120 22, 160 12" stroke="#F6D83A" stroke-width="0.8" fill="none"/>
                <path d="M20 12 Q60 6, 80 12 Q100 18, 140 12" stroke="#5C9550" stroke-width="0.6" fill="none"/>
                <circle cx="80" cy="12" r="4" fill="#DDB911" fill-opacity="0.6"/>
                <circle cx="40" cy="8"  r="2" fill="#86C3C9" fill-opacity="0.5"/>
                <circle cx="120" cy="16" r="2" fill="#86C3C9" fill-opacity="0.5"/>
                <ellipse cx="80" cy="12" rx="2" ry="4" fill="#90456e" fill-opacity="0.4" transform="rotate(30 80 12)"/>
            </svg>
        </div>

    </div>

    @push('scripts')
    <script>
        // ── Star Rating Interaction ────────────────────────────
        const stars    = document.querySelectorAll('.star');
        const rInput   = document.getElementById('rating-input');
        const rDesc    = document.getElementById('rating-desc');
        const submitBtn = document.getElementById('btn-submit-review');

        const descriptions = [
            '', 'Sangat Buruk', 'Buruk', 'Cukup Baik', 'Baik', 'Sangat Baik!'
        ];

        let currentRating = parseInt(rInput.value) || 0;

        function renderStars(hovered) {
            const val = hovered || currentRating;
            stars.forEach(s => {
                const sv = parseInt(s.dataset.value);
                s.classList.toggle('filled', sv <= val);
            });
        }

        function activateSubmit() {
            if (currentRating > 0) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor  = 'pointer';
            }
        }

        // Restore old value if any
        if (currentRating) { renderStars(0); activateSubmit(); rDesc.textContent = descriptions[currentRating]; }

        stars.forEach(star => {
            const val = parseInt(star.dataset.value);

            star.addEventListener('mouseenter', () => renderStars(val));
            star.addEventListener('mouseleave', () => renderStars(0));
            star.addEventListener('click', () => {
                currentRating = val;
                rInput.value  = val;
                renderStars(0);
                rDesc.textContent = descriptions[val];
                activateSubmit();
            });
        });

        // ── Character Counter ──────────────────────────────────
        const textarea  = document.getElementById('review-comment');
        const charCount = document.getElementById('char-count');
        textarea.addEventListener('input', () => {
            const len = textarea.value.length;
            charCount.textContent = len + ' / 1000';
            charCount.style.color = len > 900 ? '#c98ab8' : 'var(--text-muted)';
        });
    </script>
    @endpush

</x-crm-layout>
