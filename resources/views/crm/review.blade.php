<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── HEADER ──────────────────────────────────────────────── --}}
            <div>
                <a href="{{ route('crm.dashboard') }}"
                   class="back-btn mb-4 inline-flex"
                   id="btn-back-review">
                    ← Kembali ke Dashboard
                </a>
                <h1 class="font-serif text-3xl font-bold text-warm-900 mt-2" id="review-heading">
                    Beri Ulasan
                </h1>
                <p class="text-sm text-warm-500 mt-1">
                    Order #{{ $order->order_id }}
                    &bull; Selesai {{ $order->completed_at?->format('d M Y') }}
                </p>
            </div>

            {{-- ── ORDER SUMMARY CARD ──────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-5"
                 id="review-order-summary">

                {{-- Seller info --}}
                <div class="flex items-center gap-4">
                    {{-- Seller Avatar --}}
                    <div class="w-11 h-11 rounded-full bg-brand-700 text-white flex items-center justify-center
                                font-serif text-lg font-bold shrink-0">
                        {{ strtoupper(substr($order->seller?->name ?? 'S', 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="text-xs text-warm-500 mb-0.5">Penjual</div>
                        <div class="text-sm font-semibold text-warm-900">
                            {{ $order->seller?->name ?? 'Anonim' }}
                        </div>
                    </div>

                    {{-- Order total --}}
                    <div class="text-right shrink-0">
                        <div class="text-xs text-warm-500">Total</div>
                        <div class="font-serif text-lg font-bold text-warm-900">
                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- Books in order --}}
                @if($order->orderItems->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-warm-100 space-y-3">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-9 rounded bg-brand-50 border border-brand-100
                                            flex items-center justify-center shrink-0 text-xs">
                                    📖
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-warm-900 truncate">
                                        {{ $item->book?->title ?? '(buku tidak tersedia)' }}
                                    </div>
                                    <div class="text-xs text-warm-500">
                                        {{ $item->book?->author }}
                                    </div>
                                </div>
                                <div class="text-sm font-semibold text-warm-700 shrink-0">
                                    Rp{{ number_format($item->price_at_order, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── REVIEW FORM ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-6"
                 id="review-form-card">

                <h2 class="font-serif text-lg font-bold text-warm-900 mb-6 text-center">
                    Berikan Penilaian Kamu
                </h2>

                <form method="POST"
                      action="{{ route('crm.review.store', $order) }}"
                      id="review-form">
                    @csrf

                    {{-- ── Star Rating ───────────────────────────────── --}}
                    <div class="mb-6" id="rating-section">
                        <label class="block text-xs font-semibold text-warm-600 uppercase tracking-widest mb-3">
                            Penilaian
                        </label>

                        <div class="flex gap-2 justify-center" role="group"
                             aria-label="Rating bintang"
                             id="star-rating-group">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button"
                                        class="star text-3xl cursor-pointer transition-all duration-150 select-none
                                               hover:scale-125 focus:outline-none"
                                        data-value="{{ $i }}"
                                        id="star-{{ $i }}"
                                        aria-label="{{ $i }} bintang">
                                    ☆
                                </button>
                            @endfor
                        </div>

                        <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}">

                        <p id="rating-desc" class="text-xs text-warm-500 text-center mt-2 h-4">
                            Klik bintang untuk memberi nilai
                        </p>

                        @error('rating')
                            <p class="text-xs text-red-600 text-center mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Komentar ──────────────────────────────────── --}}
                    <div class="mb-6" id="comment-section">
                        <label for="review-comment"
                               class="block text-xs font-semibold text-warm-600 uppercase tracking-widest mb-2">
                            Komentar
                            <span class="font-normal normal-case text-warm-400">(opsional)</span>
                        </label>
                        <textarea name="comment"
                                  id="review-comment"
                                  rows="4"
                                  maxlength="1000"
                                  placeholder="Ceritakan pengalamanmu bertransaksi dengan penjual ini…"
                                  class="w-full bg-warm-50 border border-warm-200 rounded-xl px-4 py-3
                                         text-sm text-warm-900 placeholder-warm-400
                                         focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500
                                         transition resize-y min-h-[100px]">{{ old('comment') }}</textarea>
                        <p class="text-xs text-warm-400 text-right mt-1" id="char-count">0 / 1000</p>
                        @error('comment')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Submit ────────────────────────────────────── --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('crm.dashboard') }}"
                           class="btn-secondary"
                           id="btn-cancel-review">
                            Batal
                        </a>
                        <button type="submit"
                                class="btn-primary opacity-40 cursor-not-allowed"
                                id="btn-submit-review"
                                disabled>
                            ✓ Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // ── Star Rating Interaction ────────────────────────────────────
        const stars      = document.querySelectorAll('.star');
        const rInput     = document.getElementById('rating-input');
        const rDesc      = document.getElementById('rating-desc');
        const submitBtn  = document.getElementById('btn-submit-review');

        const descriptions = [
            '', 'Sangat Buruk', 'Buruk', 'Cukup Baik', 'Baik', 'Sangat Baik!'
        ];

        let currentRating = parseInt(rInput.value) || 0;

        function renderStars(hovered) {
            const val = hovered || currentRating;
            stars.forEach(s => {
                const sv = parseInt(s.dataset.value);
                s.textContent = sv <= val ? '★' : '☆';
                s.style.color = sv <= val ? '#174E3A' : '#d1c4a8';
            });
        }

        function activateSubmit() {
            if (currentRating > 0) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-40', 'cursor-not-allowed');
            }
        }

        // Restore old value if any
        if (currentRating) {
            renderStars(0);
            activateSubmit();
            rDesc.textContent = descriptions[currentRating];
        }

        stars.forEach(star => {
            const val = parseInt(star.dataset.value);

            star.addEventListener('mouseenter', () => renderStars(val));
            star.addEventListener('mouseleave', () => renderStars(0));
            star.addEventListener('click', () => {
                currentRating    = val;
                rInput.value     = val;
                renderStars(0);
                rDesc.textContent = descriptions[val];
                activateSubmit();
            });
        });

        // ── Character Counter ──────────────────────────────────────────
        const textarea  = document.getElementById('review-comment');
        const charCount = document.getElementById('char-count');
        textarea.addEventListener('input', () => {
            const len = textarea.value.length;
            charCount.textContent = len + ' / 1000';
            charCount.style.color = len > 900 ? '#dc2626' : '';
        });
    </script>
    @endpush
</x-app-layout>
