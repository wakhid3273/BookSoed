<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pilih Metode Pembayaran
            </h2>
            <a href="{{ route('orders.show', $order) }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Order</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-6 space-y-6">

                {{-- Detail Ringkas Order --}}
                <div class="border-b pb-4">
                    <h3 class="font-bold text-gray-800">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-sm text-gray-500">Penjual: {{ $order->seller->name }}</p>
                    <div class="mt-2 text-lg font-bold text-indigo-600">
                        Total Bayar: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>
                </div>

                @if ($errors->any())
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Form Pembayaran --}}
                <form action="{{ route('payments.store', $order) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-2">Metode Pembayaran</label>

                        <div class="space-y-3">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="E-WALLET" class="text-indigo-600 focus:ring-indigo-500"
                                       {{ old('payment_method') === 'E-WALLET' || !old('payment_method') ? 'checked' : '' }}
                                       onclick="toggleEwalletOptions(true)">
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-800">E-Wallet</span>
                                    <span class="block text-xs text-gray-500">GoPay, OVO, DANA, ShopeePay (Simulasi Instant)</span>
                                </span>
                            </label>

                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="COD" class="text-indigo-600 focus:ring-indigo-500"
                                       {{ old('payment_method') === 'COD' ? 'checked' : '' }}
                                       onclick="toggleEwalletOptions(false)">
                                <span class="ml-3">
                                    <span class="block text-sm font-medium text-gray-800">COD (Cash on Delivery)</span>
                                    <span class="block text-xs text-gray-500">Bayar tunai saat buku diserahterimakan</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Pilihan Provider E-Wallet --}}
                    <div id="ewallet-section" class="{{ old('payment_method') === 'COD' ? 'hidden' : '' }}">
                        <label for="ewallet_provider" class="block font-medium text-sm text-gray-700 mb-1">Pilih Provider E-Wallet</label>
                        <select name="ewallet_provider" id="ewallet_provider" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">-- Pilih Provider --</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" {{ old('ewallet_provider') === $provider ? 'selected' : '' }}>
                                    {{ $provider }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-md hover:bg-indigo-700">
                            Lanjutkan Pembayaran
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
