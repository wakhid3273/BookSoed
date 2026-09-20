<x-app-layout>
    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── HEADER ──────────────────────────────────────────────── --}}
            <div>
                <a href="{{ route('deliveries.show', $delivery->order_id) }}"
                   class="back-btn mb-4 inline-flex">
                    ← Kembali ke Tracking
                </a>
                <h1 class="font-serif text-2xl font-bold text-warm-900 mt-2">
                    Update Status Pengiriman
                </h1>
                <p class="text-sm text-warm-500 mt-1">
                    Order #{{ str_pad($delivery->order_id, 5, '0', STR_PAD_LEFT) }}
                </p>
            </div>

            {{-- ── FLASH STATUS ────────────────────────────────────────── --}}
            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2"
                     id="update-flash" role="alert">
                    ✓ {{ session('status') }}
                </div>
            @endif

            {{-- ── STATUS CARD ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50">
                    <h2 class="font-bold text-warm-900 text-sm">Status Saat Ini</h2>
                </div>

                <div class="px-6 py-4">
                    @php
                        $currentStatus = $delivery->delivery_status;
                        $statusClass = match($currentStatus) {
                            'completed'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'delivered'   => 'bg-brand-50 text-brand-700 border-brand-200',
                            'on_delivery', 'picked_up' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'assigned'    => 'bg-amber-100 text-amber-800 border-amber-200',
                            'processing'  => 'bg-orange-100 text-orange-800 border-orange-200',
                            default       => 'bg-warm-100 text-warm-700 border-warm-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $currentStatus)) }}
                    </span>
                </div>
            </div>

            {{-- ── UPDATE FORM ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50">
                    <h2 class="font-bold text-warm-900 text-sm">Ubah Status ke</h2>
                </div>

                <div class="p-6">
                    @php
                        $statuses = [
                            'pending'     => 'Menunggu',
                            'processing'  => 'Diproses',
                            'assigned'    => 'Kurir Ditugaskan',
                            'picked_up'   => 'Sudah Diambil',
                            'on_delivery' => 'Dalam Pengiriman',
                            'delivered'   => 'Terkirim',
                            'completed'   => 'Selesai',
                        ];
                        $statusKeys    = array_keys($statuses);
                        $currentIndex  = array_search($delivery->delivery_status, $statusKeys);
                        $allowedKeys   = array_slice($statusKeys, $currentIndex);
                    @endphp

                    <form method="POST"
                          action="{{ route('deliveries.update-status', $delivery) }}"
                          id="update-status-form">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6" id="status-select-section">
                            <label for="delivery_status"
                                   class="block text-xs font-semibold text-warm-700 uppercase tracking-widest mb-2">
                                Status Baru
                            </label>
                            <select id="delivery_status"
                                    name="delivery_status"
                                    class="w-full bg-warm-50 border border-warm-200 rounded-xl px-4 py-2.5
                                           text-sm text-warm-900
                                           focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500
                                           transition">
                                @foreach($allowedKeys as $statusKey)
                                    <option value="{{ $statusKey }}"
                                            {{ $statusKey === $delivery->delivery_status ? 'selected' : '' }}>
                                        {{ $statuses[$statusKey] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delivery_status')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="btn-primary" id="btn-save-status">
                                💾 Simpan Status
                            </button>
                            <a href="{{ route('deliveries.show', $delivery->order_id) }}"
                               class="btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-dismiss flash alert
        const flash = document.getElementById('update-flash');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.4s';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 400);
            }, 4000);
        }
    </script>
    @endpush
</x-app-layout>
