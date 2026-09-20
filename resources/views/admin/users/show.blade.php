<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between">
                <x-back-button :href="route('admin.users.index')" label="Kembali ke Pengguna" />
            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 space-y-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 border-b border-warm-100 pb-4">
                    <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-2xl shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="font-serif text-2xl font-bold text-warm-900">{{ $user->name }}</h1>
                        <p class="text-sm text-warm-500 mt-0.5">{{ $user->email }}</p>
                    </div>
                    <span class="px-3 py-1.5 text-xs font-bold rounded-lg border
                        {{ $user->role === 'admin'
                           ? 'bg-amber-100 text-amber-800 border-amber-200'
                           : 'bg-warm-100 text-warm-700 border-warm-200' }}">
                        {{ $user->role === 'admin' ? '🛡️ Admin ERP' : '🎓 Mahasiswa' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-400 block text-[11px] uppercase tracking-wider font-semibold mb-1">Bergabung</span>
                        <span class="font-semibold text-warm-900 text-sm">{{ $user->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="bg-warm-50 rounded-xl p-3.5 border border-warm-100">
                        <span class="text-warm-400 block text-[11px] uppercase tracking-wider font-semibold mb-1">Role</span>
                        <span class="font-semibold text-warm-900 text-sm capitalize">{{ $user->role }}</span>
                    </div>
                </div>
            </div>

            <!-- Activity Stats -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-warm-200 p-5 shadow-xs text-center transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="text-3xl font-bold text-warm-900 font-mono">{{ $user->books_count }}</div>
                    <div class="text-xs text-warm-500 mt-1.5 font-medium">📚 Listing Buku</div>
                </div>
                <div class="bg-white rounded-2xl border border-warm-200 p-5 shadow-xs text-center transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="text-3xl font-bold text-brand-700 font-mono">{{ $user->buyer_orders_count }}</div>
                    <div class="text-xs text-warm-500 mt-1.5 font-medium">📦 Order sebagai Buyer</div>
                </div>
                <div class="bg-white rounded-2xl border border-warm-200 p-5 shadow-xs text-center transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <div class="text-3xl font-bold text-amber-700 font-mono">{{ $user->seller_orders_count }}</div>
                    <div class="text-xs text-warm-500 mt-1.5 font-medium">📬 Order sebagai Seller</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
