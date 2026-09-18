<x-app-layout>
    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8 space-y-6">

                {{-- Detail Ringkas Order --}}
                <div class="border-b border-warm-200 pb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-mono text-warm-500">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <h1 class="font-serif text-2xl font-bold text-warm-900 mt-0.5">Pilih Metode Pembayaran</h1>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="text-xs font-semibold text-brand-700 hover:underline">
                            ← Batal
                        </a>
                    </div>
                    
                    <div class="mt-4 p-4 bg-warm-50 rounded-xl border border-warm-200 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-warm-500">Penjual: <strong class="text-warm-900">{{ $order->seller->name }}</strong></p>
                            <p class="text-xs text-warm-500 mt-0.5">Total Tagihan (termasuk service fee)</p>
                        </div>
                        <div class="text-xl font-bold text-brand-700">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Form Pembayaran --}}
                <form action="{{ route('payments.store', $order) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-warm-800 uppercase tracking-wider mb-3">Pilih Opsi Pembayaran</label>

                        <div class="space-y-3">
                            <label class="flex items-start p-4 border border-warm-200 rounded-xl cursor-pointer hover:bg-warm-50/60 transition">
                                <input type="radio" name="payment_method" value="E-WALLET" class="mt-1 text-brand-700 focus:ring-brand-500"
                                       {{ old('payment_method') === 'E-WALLET' || !old('payment_method') ? 'checked' : '' }}
                                       onclick="toggleEwalletOptions(true)">
                                <span class="ml-3 flex-1">
                                    <span class="block text-sm font-bold text-warm-900">E-Wallet (Instan & Otomatis)</span>
                                    <span class="block text-xs text-warm-600 mt-0.5">Bayar via GoPay, OVO, DANA, atau ShopeePay</span>
                                </span>
                            </label>

                            <label class="flex items-start p-4 border border-warm-200 rounded-xl cursor-pointer hover:bg-warm-50/60 transition">
                                <input type="radio" name="payment_method" value="COD" class="mt-1 text-brand-700 focus:ring-brand-500"
                                       {{ old('payment_method') === 'COD' ? 'checked' : '' }}
                                       onclick="toggleEwalletOptions(false)">
                                <span class="ml-3 flex-1">
                                    <span class="block text-sm font-bold text-warm-900">COD (Cash on Delivery / Bayar di Tempat)</span>
                                    <span class="block text-xs text-warm-600 mt-0.5">Bayar tunai saat buku diserahterimakan oleh kurir/penjual</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Provider E-Wallet --}}
                    <div id="ewallet-section" class="{{ old('payment_method') === 'COD' ? 'hidden' : '' }} p-4 bg-brand-50/50 border border-brand-200 rounded-xl space-y-2">
                        <label for="ewallet_provider" class="block text-xs font-semibold text-brand-900">Pilih Provider Dompet Digital</label>
                        <select name="ewallet_provider" id="ewallet_provider"
                                class="w-full bg-white border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                            <option value="">-- Pilih Provider E-Wallet --</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" {{ old('ewallet_provider') === $provider ? 'selected' : '' }}>
                                    📱 {{ $provider }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-warm-200">
                        <a href="{{ route('orders.show', $order) }}" class="px-4 py-2.5 border border-warm-200 text-warm-700 text-xs font-semibold rounded-xl hover:bg-warm-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-xs rounded-xl shadow-xs transition duration-150">
                            Konfirmasi Pembayaran →
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleEwalletOptions(show) {
            const section = document.getElementById('ewallet-section');
            if (show) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
