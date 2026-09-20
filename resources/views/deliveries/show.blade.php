<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── HEADER ──────────────────────────────────────────────── --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('orders.show', $order) }}" class="back-btn">
                    ← Kembali ke Detail Order
                </a>
                <span class="text-xs font-mono text-warm-500">
                    Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            {{-- ── FLASH STATUS ────────────────────────────────────────── --}}
            @if(session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2"
                     id="delivery-flash" role="alert">
                    ✓ {{ session('status') }}
                </div>
            @endif

            {{-- ── DELIVERY STATUS CARD ─────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-warm-100 bg-warm-50/50 flex items-center justify-between">
                    <div>
                        <h1 class="font-serif text-xl font-bold text-warm-900">Tracking Pengiriman</h1>
                        <p class="text-xs text-warm-500 mt-0.5">
                            Metode: <span class="font-semibold uppercase">{{ $order->delivery->delivery_method }}</span>
                        </p>
                    </div>

                    @php
                        $deliveryStatus = $order->delivery->delivery_status;
                        $statusClass = match($deliveryStatus) {
                            'completed'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'delivered'   => 'bg-brand-50 text-brand-700 border-brand-200',
                            'on_delivery', 'picked_up' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'assigned'    => 'bg-amber-100 text-amber-800 border-amber-200',
                            'processing'  => 'bg-orange-100 text-orange-800 border-orange-200',
                            default       => 'bg-warm-100 text-warm-700 border-warm-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $deliveryStatus)) }}
                    </span>
                </div>

                {{-- Delivery Status Timeline --}}
                @php
                    $statuses = ['pending', 'processing', 'assigned', 'picked_up', 'on_delivery', 'delivered', 'completed'];
                    $currentIdx = array_search($deliveryStatus, $statuses);
                    $statusLabels = [
                        'pending'     => 'Menunggu',
                        'processing'  => 'Diproses',
                        'assigned'    => 'Kurir Ditugaskan',
                        'picked_up'   => 'Sudah Diambil',
                        'on_delivery' => 'Dalam Pengiriman',
                        'delivered'   => 'Terkirim',
                        'completed'   => 'Selesai',
                    ];
                @endphp

                <div class="px-6 py-5">
                    <div class="flex items-center gap-1 overflow-x-auto pb-2" id="delivery-timeline">
                        @foreach($statuses as $idx => $status)
                            @php
                                $isDone    = $idx <= $currentIdx;
                                $isCurrent = $idx === $currentIdx;
                            @endphp
                            <div class="flex items-center shrink-0">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold border-2
                                                {{ $isCurrent ? 'bg-brand-700 border-brand-700 text-white' :
                                                   ($isDone ? 'bg-emerald-100 border-emerald-400 text-emerald-700' :
                                                              'bg-warm-100 border-warm-200 text-warm-400') }}">
                                        @if($isDone && !$isCurrent) ✓ @else {{ $idx + 1 }} @endif
                                    </div>
                                    <span class="text-[10px] text-center w-16 leading-tight
                                                 {{ $isCurrent ? 'text-brand-700 font-semibold' :
                                                    ($isDone ? 'text-emerald-700' : 'text-warm-400') }}">
                                        {{ $statusLabels[$status] }}
                                    </span>
                                </div>
                                @if(!$loop->last)
                                    <div class="w-6 h-0.5 mb-5 mx-0.5 shrink-0
                                                {{ $idx < $currentIdx ? 'bg-emerald-300' : 'bg-warm-200' }}">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Location Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-6 pb-6">
                    <div class="bg-warm-50 rounded-xl border border-warm-200 p-4">
                        <h4 class="text-xs font-semibold text-warm-600 uppercase tracking-widest mb-3">
                            Detail Lokasi
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex gap-2">
                                <span class="text-warm-500 shrink-0">Dari:</span>
                                <span class="font-medium text-warm-900">{{ $order->delivery->pickup_location }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-warm-500 shrink-0">Ke:</span>
                                <span class="font-medium text-warm-900">{{ $order->delivery->destination }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->delivery->delivery_method === 'jeksoed')
                        <div class="bg-brand-50 rounded-xl border border-brand-200 p-4">
                            <h4 class="text-xs font-semibold text-brand-700 uppercase tracking-widest mb-3">
                                Info Kurir Jeksoed
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex gap-2">
                                    <span class="text-warm-500 shrink-0">Order ID:</span>
                                    <span class="font-medium text-warm-900">{{ $order->delivery->jeksoed_order_id ?? '-' }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <span class="text-warm-500 shrink-0">Nama:</span>
                                    <span class="font-medium text-warm-900">{{ $order->delivery->driver_name ?? '-' }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <span class="text-warm-500 shrink-0">Telepon:</span>
                                    <span class="font-medium text-warm-900">{{ $order->delivery->driver_phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Action: Update Status (for seller) --}}
                <div class="px-6 pb-6 border-t border-warm-100 pt-4">
                    <a href="{{ route('deliveries.update-status', $order->delivery) }}"
                       class="btn-sm-primary text-xs">
                        📦 Update Status Pengiriman
                    </a>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-dismiss flash alert
        const flash = document.getElementById('delivery-flash');
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
