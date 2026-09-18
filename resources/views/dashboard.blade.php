<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="border-b border-warm-200 pb-4">
                <h1 class="font-serif text-2xl font-bold text-warm-900">Dashboard Pengguna</h1>
                <p class="text-xs text-warm-700 mt-1">Selamat datang kembali di BookSoed, <strong>{{ Auth::user()->name }}</strong>!</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <a href="{{ route('books.index') }}" class="p-6 bg-white rounded-2xl border border-warm-200 hover:border-brand-300 shadow-xs hover:shadow transition group">
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        📖
                    </div>
                    <h2 class="font-serif font-bold text-warm-900 text-lg group-hover:text-brand-700">Marketplace Buku</h2>
                    <p class="text-xs text-warm-600 mt-1">Jelajahi dan temukan buku bekas untuk kuliahmu.</p>
                </a>

                <a href="{{ route('books.create') }}" class="p-6 bg-white rounded-2xl border border-warm-200 hover:border-brand-300 shadow-xs hover:shadow transition group">
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        ➕
                    </div>
                    <h2 class="font-serif font-bold text-warm-900 text-lg group-hover:text-brand-700">Jual Buku</h2>
                    <p class="text-xs text-warm-600 mt-1">Pasang listing buku bekas yang tidak terpakai.</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
