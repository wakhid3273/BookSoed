<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">ERP Admin Area</h1>
                    <p class="text-sm text-warm-800 mt-1">Modul Administrasi Transaksi & Laporan Keuangan ERP BookSoed</p>
                </div>
                <span class="px-3 py-1 bg-amber-100 text-amber-900 text-xs font-semibold rounded-full border border-amber-200">
                    Role: Admin ERP
                </span>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="font-serif text-xl font-bold text-warm-900">Selamat Datang di Portal Admin ERP</h2>
                    <p class="text-xs text-warm-700 mt-1 leading-relaxed max-w-2xl">
                        Sebagai Administrator ERP, Anda memiliki wewenang untuk mengawasi seluruh aktivitas transaksi, katalog buku marketplace, dan arus pembayaran sistem.
                    </p>
                </div>

                {{-- Quick Nav Link Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <a href="{{ route('books.index') }}" class="p-5 bg-warm-50 hover:bg-brand-50 border border-warm-200 hover:border-brand-200 rounded-xl transition group">
                        <div class="text-2xl mb-2">📖</div>
                        <h3 class="font-bold text-warm-900 text-sm group-hover:text-brand-700">Marketplace Buku</h3>
                        <p class="text-xs text-warm-500 mt-1">Pantau seluruh katalog buku yang tersedia</p>
                    </a>

                    <a href="{{ route('orders.index') }}" class="p-5 bg-warm-50 hover:bg-brand-50 border border-warm-200 hover:border-brand-200 rounded-xl transition group">
                        <div class="text-2xl mb-2">📦</div>
                        <h3 class="font-bold text-warm-900 text-sm group-hover:text-brand-700">Manajemen Order</h3>
                        <p class="text-xs text-warm-500 mt-1">Kelola transaksi dan status pesanan</p>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="p-5 bg-warm-50 hover:bg-brand-50 border border-warm-200 hover:border-brand-200 rounded-xl transition group">
                        <div class="text-2xl mb-2">⚙️</div>
                        <h3 class="font-bold text-warm-900 text-sm group-hover:text-brand-700">Pengaturan Akun</h3>
                        <p class="text-xs text-warm-500 mt-1">Kelola profil admin dan akun</p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
