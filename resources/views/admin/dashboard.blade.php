<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">ERP Admin Area</h1>
                    <p class="text-sm text-warm-600 mt-1">Dashboard Admin ERP · Ringkasan operasional marketplace BookSoed Unsoed · {{ now()->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-lg border border-amber-200">
                    🛡️ Role: Admin ERP
                </span>
            </div>

            <!-- ── STAT CARDS ─────────────────────────────────────────── -->

            {{-- Users --}}
            <div>
                <h2 class="text-xs font-bold text-warm-500 uppercase tracking-widest mb-3">👥 Pengguna Terdaftar</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-warm-900 font-mono">{{ number_format($totalUsers) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Total Pengguna</div>
                    </div>
                    <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-warm-900 font-mono">{{ number_format($totalStudents) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Mahasiswa</div>
                    </div>
                    <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-amber-700 font-mono">{{ number_format($totalAdmins) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Admin ERP</div>
                    </div>
                </div>
            </div>

            {{-- Listings --}}
            <div>
                <h2 class="text-xs font-bold text-warm-500 uppercase tracking-widest mb-3">📚 Listing Buku</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-warm-900 font-mono">{{ number_format($totalBooks) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Total Listing</div>
                    </div>
                    <div class="bg-white border border-emerald-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-emerald-700 font-mono">{{ number_format($availableBooks) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Tersedia</div>
                    </div>
                    <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-amber-700 font-mono">{{ number_format($reservedBooks) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Dipesan</div>
                    </div>
                    <div class="bg-white border border-warm-200 rounded-2xl p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div class="text-3xl font-bold text-warm-700 font-mono">{{ number_format($soldBooks) }}</div>
                        <div class="text-xs text-warm-500 mt-1 font-medium">Terjual</div>
                    </div>
                </div>
            </div>

            {{-- Orders + Payments side by side --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Orders --}}
                <div>
                    <h2 class="text-xs font-bold text-warm-500 uppercase tracking-widest mb-3">📦 Transaksi Order</h2>
                    <div class="bg-white border border-warm-200 rounded-2xl p-6 shadow-xs space-y-4">
                        <div class="flex justify-between items-baseline">
                            <span class="text-4xl font-bold text-warm-900 font-mono">{{ number_format($totalOrders) }}</span>
                            <span class="text-xs text-warm-500 font-medium">Total Order</span>
                        </div>
                        <div class="space-y-2 pt-2 border-t border-warm-100">
                            @php
                                $orderRows = [
                                    ['label' => 'Menunggu (PENDING)',    'count' => $pendingOrders,    'color' => 'bg-amber-400'],
                                    ['label' => 'Diproses (PROCESSING)', 'count' => $processingOrders, 'color' => 'bg-sky-400'],
                                    ['label' => 'Selesai (COMPLETED)',   'count' => $completedOrders,  'color' => 'bg-emerald-400'],
                                    ['label' => 'Dibatalkan (CANCELLED)','count' => $cancelledOrders,  'color' => 'bg-rose-400'],
                                ];
                                $maxOrder = max($totalOrders, 1);
                            @endphp
                            @foreach ($orderRows as $row)
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="w-32 text-warm-600 font-medium shrink-0">{{ $row['label'] }}</span>
                                    <div class="flex-1 bg-warm-100 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $row['color'] }} h-2 rounded-full transition-all duration-500"
                                             style="width: {{ $totalOrders > 0 ? round(($row['count'] / $maxOrder) * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="font-bold text-warm-900 w-6 text-right">{{ $row['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Payments + Transaction Value --}}
                <div>
                    <h2 class="text-xs font-bold text-warm-500 uppercase tracking-widest mb-3">💳 Pembayaran & Nilai Transaksi</h2>
                    <div class="bg-white border border-warm-200 rounded-2xl p-6 shadow-xs space-y-4">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-emerald-700 font-mono">{{ number_format($paidPayments) }}</div>
                                <div class="text-[11px] text-warm-500 font-medium mt-0.5">LUNAS</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-amber-700 font-mono">{{ number_format($pendingPayments) }}</div>
                                <div class="text-[11px] text-warm-500 font-medium mt-0.5">MENUNGGU</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-rose-700 font-mono">{{ number_format($failedPayments) }}</div>
                                <div class="text-[11px] text-warm-500 font-medium mt-0.5">GAGAL</div>
                            </div>
                        </div>
                        <div class="border-t border-warm-100 pt-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-warm-500 font-medium">Total Nilai Transaksi</span>
                                <span class="font-bold text-brand-700 text-sm">Rp {{ number_format($totalTransactionValue, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-warm-500 font-medium">Total Service Fee Platform</span>
                                <span class="font-bold text-warm-900 text-sm">Rp {{ number_format($totalServiceFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-warm-100">
                                <span class="text-xs text-warm-500 font-medium">Total Pembayaran Tercatat</span>
                                <span class="font-semibold text-warm-800 text-sm">{{ number_format($totalPayments) }} transaksi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── RECENT ORDERS ──────────────────────────────────────── -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-xs font-bold text-warm-500 uppercase tracking-widest">📋 Order Terbaru</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">Lihat Semua →</a>
                </div>
                <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                    @if ($recentOrders->isEmpty())
                        <div class="p-10 text-center text-sm text-warm-500">Belum ada transaksi order.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-warm-50 border-b border-warm-200 text-xs font-semibold text-warm-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-5 py-3">Order</th>
                                        <th class="px-4 py-3">Buyer</th>
                                        <th class="px-4 py-3">Seller</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                        <th class="px-4 py-3 text-center">Status</th>
                                        <th class="px-4 py-3 text-center">Pembayaran</th>
                                        <th class="px-5 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-warm-100 text-warm-900">
                                    @foreach ($recentOrders as $order)
                                        <tr class="hover:bg-warm-50 transition">
                                            <td class="px-5 py-3 font-mono text-xs font-semibold text-warm-700">
                                                #BS-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                                <div class="text-[10px] text-warm-400 font-normal">{{ $order->order_date->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-warm-800">{{ $order->buyer->name }}</td>
                                            <td class="px-4 py-3 text-xs text-warm-800">{{ $order->seller->name }}</td>
                                            <td class="px-4 py-3 text-right text-xs font-bold text-brand-700">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @include('orders._status-badge', ['status' => $order->status])
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($order->payment)
                                                    @include('payments._status-badge', ['status' => $order->payment->payment_status])
                                                @else
                                                    <span class="text-[11px] text-warm-400 font-medium">—</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3 text-center">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                   class="px-2.5 py-1 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-semibold rounded-lg transition">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ── RECENT USERS ───────────────────────────────────────── -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-xs font-bold text-warm-500 uppercase tracking-widest">👤 Pengguna Terbaru</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">Lihat Semua →</a>
                </div>
                <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                    @if ($recentUsers->isEmpty())
                        <div class="p-10 text-center text-sm text-warm-500">Belum ada pengguna terdaftar.</div>
                    @else
                        <div class="divide-y divide-warm-100">
                            @foreach ($recentUsers as $user)
                                <div class="flex items-center justify-between px-6 py-3.5 hover:bg-warm-50 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-warm-900">{{ $user->name }}</p>
                                            <p class="text-xs text-warm-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full border
                                            {{ $user->role === 'admin'
                                               ? 'bg-amber-100 text-amber-800 border-amber-200'
                                               : 'bg-warm-100 text-warm-700 border-warm-200' }}">
                                            {{ $user->role === 'admin' ? 'Admin' : 'Mahasiswa' }}
                                        </span>
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="text-xs font-semibold text-brand-700 hover:underline">Detail →</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
