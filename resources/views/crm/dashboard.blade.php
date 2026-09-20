<x-crm-layout>
    <x-slot name="title">Dashboard CRM</x-slot>

    {{-- ══════════════════════════════════════════════════════════════
         HERO PROFILE SECTION
    ══════════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom: 2.5rem">

        {{-- Profile Hero Card --}}
        <div class="lg-card" style="padding: 2rem; display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">

            {{-- Large Avatar --}}
            <div style="
                width: 80px; height: 80px; border-radius: 50%;
                border: 2px solid rgba(246,216,58,0.5);
                background: linear-gradient(135deg, #4B7416 0%, #5C9550 60%, #86C3C9 100%);
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
                box-shadow: 0 0 24px rgba(92,149,80,0.3);
                font-family: 'Cormorant Garamond', serif;
                font-size: 2rem; font-weight: 700; color: #F6D83A;
            " id="dashboard-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>

            {{-- Profile Info --}}
            <div style="flex: 1; min-width: 200px;">
                <div style="display:flex; align-items:center; gap: 0.75rem; flex-wrap:wrap; margin-bottom: 0.4rem;">
                    <h1 class="nouveau-heading" style="font-size: 1.7rem; margin: 0;">
                        {{ Auth::user()->name }}
                    </h1>
                    <span class="lg-pill lg-pill-leaf">✓ Civitas Unsoed</span>
                </div>
                <p class="text-muted" style="margin: 0; font-size: 0.85rem;">{{ Auth::user()->email }}</p>
                <p style="margin: 0.3rem 0 0; font-size: 0.78rem; color: rgba(134,195,201,0.7);">
                    Mahasiswa / Civitas Akademika Universitas Jenderal Soedirman
                </p>
            </div>

            {{-- Quick Actions --}}
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; flex-shrink: 0;">
                <a href="{{ route('crm.wishlist') }}" class="lg-btn lg-btn-plum" id="btn-wishlist-hero">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M7 12S1 8.5 1 4.5a3 3 0 016 0 3 3 0 016 0C13 8.5 7 12 7 12z" stroke="currentColor" stroke-width="1.2" fill="none"/>
                    </svg>
                    Wishlist
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         STATISTICS ROW
    ══════════════════════════════════════════════════════════════ --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2.5rem;" id="stats-row">

        {{-- Stat: Pembelian Selesai --}}
        <div class="lg-card stat-card" id="stat-purchases">
            <div class="stat-number text-petal">{{ $purchaseCount }}</div>
            <div class="stat-label">Pembelian Selesai</div>
            <div style="margin-top: 0.75rem;">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" style="opacity:0.3">
                    <path d="M5 6h18M5 6l2 16h14l2-16" stroke="#F6D83A" stroke-width="1.3" fill="none"/>
                    <circle cx="10" cy="24" r="1.5" fill="#F6D83A"/>
                    <circle cx="18" cy="24" r="1.5" fill="#F6D83A"/>
                    <path d="M10 13l3 3 5-5" stroke="#5C9550" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        {{-- Stat: Penjualan Selesai --}}
        <div class="lg-card stat-card" id="stat-sales">
            <div class="stat-number text-leaf">{{ $salesCount }}</div>
            <div class="stat-label">Buku Terjual</div>
            <div style="margin-top: 0.75rem;">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" style="opacity:0.3">
                    <rect x="4" y="4" width="20" height="20" rx="3" stroke="#5C9550" stroke-width="1.3" fill="none"/>
                    <path d="M9 9h10M9 14h7M9 19h5" stroke="#8fcf80" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        {{-- Stat: Wishlist --}}
        <div class="lg-card stat-card" id="stat-wishlist">
            <div class="stat-number text-plum">{{ $wishlistCount }}</div>
            <div class="stat-label">Wishlist</div>
            <div style="margin-top: 0.75rem;">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" style="opacity:0.3">
                    <path d="M14 23S4 17 4 10a6 6 0 0110 4.47A6 6 0 0124 10c0 7-10 13-10 13z" stroke="#c98ab8" stroke-width="1.3" fill="none"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════════
         HISTORY SECTION (2 columns)
    ══════════════════════════════════════════════════════════════ --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;" id="history-grid">

        {{-- ── PURCHASE HISTORY ──────────────────────────── --}}
        <div class="lg-card" id="purchase-history-card">
            {{-- Card inner shine handled by ::before/::after --}}
            <div style="padding: 1.5rem 1.5rem 0.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2 class="nouveau-subheading" style="margin:0;">Riwayat Pembelian</h2>
                    <span class="lg-pill lg-pill-petal">{{ $purchaseCount }} total</span>
                </div>

                @forelse($purchaseHistory as $order)
                    <div class="order-row" id="purchase-row-{{ $order->order_id }}">
                        {{-- Book icon --}}
                        <div style="
                            width: 38px; height: 48px; flex-shrink: 0;
                            background: linear-gradient(135deg, rgba(75,116,22,0.5), rgba(12,20,16,0.9));
                            border-radius: 4px;
                            border: 1px solid rgba(246,216,58,0.15);
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <rect x="2" y="1" width="12" height="14" rx="1.5" stroke="#DDB911" stroke-width="1" fill="none"/>
                                <path d="M5 5h6M5 8h4" stroke="#5C9550" stroke-width="0.9"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.83rem; font-weight:500; color: var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $order->orderItems->first()?->book?->title ?? 'Pesanan #'.$order->order_id }}
                            </div>
                            <div style="font-size:0.72rem; color: var(--text-muted);">
                                {{ $order->order_date?->format('d M Y') }}
                            </div>
                        </div>

                        {{-- Status & Review --}}
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.3rem; flex-shrink:0;">
                            @php
                                $statusClass = match($order->order_status) {
                                    'completed' => 'lg-pill-leaf',
                                    'pending'   => 'lg-pill-petal',
                                    'cancelled' => 'lg-pill-plum',
                                    default     => 'lg-pill-muted',
                                };
                            @endphp
                            <span class="lg-pill {{ $statusClass }}">{{ ucfirst($order->order_status) }}</span>
                            @if($order->order_status === 'completed' && !$order->review)
                                <a href="{{ route('crm.review.create', $order) }}"
                                   class="lg-btn lg-btn-ghost"
                                   style="padding: 0.15rem 0.6rem; font-size: 0.7rem;"
                                   id="btn-review-{{ $order->order_id }}">
                                    + Beri Ulasan
                                </a>
                            @elseif($order->review)
                                <span style="font-size:0.7rem; color: var(--stamen);">
                                    ★ {{ $order->review->rating }}/5
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding: 2rem 0; color: var(--text-muted);">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" style="margin: 0 auto 0.75rem; opacity:0.3; display:block;">
                            <circle cx="18" cy="18" r="16" stroke="currentColor" stroke-width="1"/>
                            <path d="M13 18h10M18 13v10" stroke="currentColor" stroke-width="1.3"/>
                        </svg>
                        <p style="font-size:0.82rem; margin:0;">Belum ada pembelian</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── SALES HISTORY ─────────────────────────────── --}}
        <div class="lg-card" id="sales-history-card">
            <div style="padding: 1.5rem 1.5rem 0.5rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2 class="nouveau-subheading" style="margin:0;">Riwayat Penjualan</h2>
                    <span class="lg-pill lg-pill-leaf">{{ $salesCount }} terjual</span>
                </div>

                @forelse($salesHistory as $order)
                    <div class="order-row" id="sales-row-{{ $order->order_id }}">
                        <div style="
                            width: 38px; height: 48px; flex-shrink: 0;
                            background: linear-gradient(135deg, rgba(92,149,80,0.4), rgba(12,20,16,0.9));
                            border-radius: 4px;
                            border: 1px solid rgba(92,149,80,0.25);
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <rect x="2" y="1" width="12" height="14" rx="1.5" stroke="#5C9550" stroke-width="1" fill="none"/>
                                <path d="M5 5h6M5 8h4" stroke="#DDB911" stroke-width="0.9"/>
                            </svg>
                        </div>

                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.83rem; font-weight:500; color: var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $order->orderItems->first()?->book?->title ?? 'Pesanan #'.$order->order_id }}
                            </div>
                            <div style="font-size:0.72rem; color: var(--text-muted);">
                                Pembeli: {{ $order->buyer?->name }}
                                &bull; {{ $order->order_date?->format('d M Y') }}
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.3rem; flex-shrink:0;">
                            @php
                                $statusClass = match($order->order_status) {
                                    'completed' => 'lg-pill-leaf',
                                    'pending'   => 'lg-pill-petal',
                                    default     => 'lg-pill-muted',
                                };
                            @endphp
                            <span class="lg-pill {{ $statusClass }}">{{ ucfirst($order->order_status) }}</span>
                            <span style="font-size:0.78rem; color: var(--petal); font-family: 'Cormorant Garamond', serif;">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding: 2rem 0; color: var(--text-muted);">
                        <p style="font-size:0.82rem; margin:0;">Belum ada penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Responsive fix for small screens --}}
    <style>
        @media (max-width: 700px) {
            #history-grid { grid-template-columns: 1fr !important; }
            #stats-row    { grid-template-columns: repeat(3, 1fr) !important; }
        }
    </style>

</x-crm-layout>
