<x-app-layout>
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-b from-brand-50/60 to-warm-50 border-b border-warm-200 py-16 lg:py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center space-y-6">

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-brand-200 text-brand-700 text-xs font-semibold shadow-xs">
                    <span>🎓 Dedicated for Civitas Unsoed</span>
                </div>

                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-warm-900 tracking-tight leading-tight">
                    Temukan buku bekas layak,<br>
                    <span class="text-brand-700 italic font-normal">jual yang tak terpakai.</span>
                </h1>

                <p class="text-base sm:text-lg text-warm-800 leading-relaxed max-w-2xl mx-auto">
                    Platform khusus mahasiswa Universitas Jenderal Soedirman untuk saling bertukar, membeli, dan menjual buku kuliah bekas secara langsung, aman, dan hemat.
                </p>

                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('books.index') }}"
                       class="w-full sm:w-auto px-7 py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-base rounded-xl shadow-md hover:shadow-lg transition duration-200 text-center">
                        📖 Jelajahi Buku
                    </a>
                    <a href="{{ route('books.create') }}"
                       class="w-full sm:w-auto px-7 py-3.5 bg-white border border-warm-300 hover:bg-warm-100 text-warm-900 font-semibold text-base rounded-xl transition duration-200 text-center shadow-xs">
                        ➕ Jual Buku Sekarang
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- How it Works Section --}}
    <div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-warm-900">Bagaimana BookSoed Bekerja?</h2>
            <p class="text-sm text-warm-800 mt-2">Proses simpel transaksi buku antar sesama mahasiswa di kampus</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-warm-200 shadow-xs hover:border-brand-200 transition">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl font-bold mb-4">1</div>
                <h3 class="font-bold text-warm-900 mb-2">Cari Buku</h3>
                <p class="text-xs text-warm-800 leading-relaxed">Temukan buku kuliah sesuai ketersediaan, mata kuliah, atau kategori yang dibutuhkan.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-warm-200 shadow-xs hover:border-brand-200 transition">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl font-bold mb-4">2</div>
                <h3 class="font-bold text-warm-900 mb-2">Pesan Langsung</h3>
                <p class="text-xs text-warm-800 leading-relaxed">Buat order langsung ke penjual sesama mahasiswa Unsoed tanpa perantara rumit.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-warm-200 shadow-xs hover:border-brand-200 transition">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl font-bold mb-4">3</div>
                <h3 class="font-bold text-warm-900 mb-2">Pilih Pembayaran</h3>
                <p class="text-xs text-warm-800 leading-relaxed">Dukungan E-Wallet (GoPay, DANA, OVO, ShopeePay) atau opsi Bayar di Tempat (COD).</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-warm-200 shadow-xs hover:border-brand-200 transition">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl font-bold mb-4">4</div>
                <h3 class="font-bold text-warm-900 mb-2">Serah Terima</h3>
                <p class="text-xs text-warm-800 leading-relaxed">Dapatkan buku kuliah impianmu dengan harga ramah kantong mahasiswa!</p>
            </div>
        </div>
    </div>
</x-app-layout>
