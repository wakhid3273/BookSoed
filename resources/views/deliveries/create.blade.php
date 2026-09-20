<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── HEADER ──────────────────────────────────────────────── --}}
            <div>
                <a href="{{ route('orders.show', $order) }}"
                   class="back-btn mb-4 inline-flex">
                    ← Kembali ke Detail Order
                </a>
                <h1 class="font-serif text-2xl font-bold text-warm-900 mt-2">
                    Pilih Metode Pengiriman
                </h1>
                <p class="text-sm text-warm-500 mt-1">
                    Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}
                </p>
            </div>

            {{-- ── FLASH ERROR ─────────────────────────────────────────── --}}
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ── DELIVERY FORM ───────────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50">
                    <h2 class="font-bold text-warm-900 text-sm">Detail Pengiriman</h2>
                </div>

                <div class="p-6 space-y-6">
                    <form method="POST"
                          action="{{ route('deliveries.store', $order) }}"
                          class="space-y-6"
                          id="delivery-form">
                        @csrf

                        {{-- ── Metode Pengiriman ────────────────────── --}}
                        <div id="delivery-method-section">
                            <span class="block text-xs font-semibold text-warm-700 uppercase tracking-widest mb-3">
                                Metode Pengiriman
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- COD Option --}}
                                <label for="delivery-cod"
                                       class="relative flex items-start gap-3 p-4 rounded-xl border border-warm-200
                                              bg-warm-50 cursor-pointer hover:border-brand-300 hover:bg-brand-50/30
                                              transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                                    <input id="delivery-cod"
                                           name="delivery_method"
                                           type="radio"
                                           value="cod"
                                           required
                                           class="mt-0.5 h-4 w-4 text-brand-700 border-warm-300
                                                  focus:ring-brand-500 focus:ring-2">
                                    <div>
                                        <div class="text-sm font-semibold text-warm-900">COD (Bayar di Tempat)</div>
                                        <div class="text-xs text-warm-500 mt-0.5">Gratis &bull; Serah terima langsung</div>
                                    </div>
                                </label>

                                {{-- Jeksoed Option --}}
                                <label for="delivery-jeksoed"
                                       class="relative flex items-start gap-3 p-4 rounded-xl border border-warm-200
                                              bg-warm-50 cursor-pointer hover:border-brand-300 hover:bg-brand-50/30
                                              transition has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                                    <input id="delivery-jeksoed"
                                           name="delivery_method"
                                           type="radio"
                                           value="jeksoed"
                                           class="mt-0.5 h-4 w-4 text-brand-700 border-warm-300
                                                  focus:ring-brand-500 focus:ring-2">
                                    <div>
                                        <div class="text-sm font-semibold text-warm-900">Jeksoed</div>
                                        <div class="text-xs text-warm-500 mt-0.5">Rp 5.000 &bull; Jasa antar kampus</div>
                                    </div>
                                </label>
                            </div>

                            @error('delivery_method')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ── Lokasi Penjemputan ───────────────────── --}}
                        <div id="pickup-section">
                            <label for="pickup_location"
                                   class="block text-xs font-semibold text-warm-700 uppercase tracking-widest mb-2">
                                Lokasi Penjemputan
                            </label>
                            <input type="text"
                                   name="pickup_location"
                                   id="pickup_location"
                                   value="{{ old('pickup_location') }}"
                                   placeholder="Contoh: Gedung A, Fakultas Ekonomi Unsoed"
                                   required
                                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-4 py-2.5
                                          text-sm text-warm-900 placeholder-warm-400
                                          focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500
                                          transition">
                            @error('pickup_location')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ── Tujuan ───────────────────────────────── --}}
                        <div id="destination-section">
                            <label for="destination"
                                   class="block text-xs font-semibold text-warm-700 uppercase tracking-widest mb-2">
                                Tujuan Pengiriman
                            </label>
                            <input type="text"
                                   name="destination"
                                   id="destination"
                                   value="{{ old('destination') }}"
                                   placeholder="Contoh: Asrama Putri, Jl. HR Bunyamin No. 10"
                                   required
                                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-4 py-2.5
                                          text-sm text-warm-900 placeholder-warm-400
                                          focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500
                                          transition">
                            @error('destination')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ── Submit ───────────────────────────────── --}}
                        <div class="flex items-center justify-end gap-3 pt-2 border-t border-warm-100">
                            <a href="{{ route('orders.show', $order) }}" class="btn-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn-primary" id="btn-confirm-delivery">
                                Konfirmasi Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
