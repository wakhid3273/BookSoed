<x-app-layout>
    {{-- ===== HERO SECTION ===== --}}
    <div class="relative bg-gradient-to-b from-brand-50/70 to-warm-50 border-b border-warm-200 py-20 lg:py-28 overflow-hidden">

        {{-- Background subtle pattern --}}
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
             style="background-image: repeating-linear-gradient(0deg, #174E3A, #174E3A 1px, transparent 1px, transparent 60px), repeating-linear-gradient(90deg, #174E3A, #174E3A 1px, transparent 1px, transparent 60px);">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center space-y-7">

                {{-- Badge --}}
                <div class="hero-tagline inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-brand-200 text-brand-700 text-xs font-semibold shadow-sm">
                    <span>🎓 Dedicated for Civitas Unsoed</span>
                </div>

                {{-- Wordmark with staggered animation --}}
                <h1 class="font-serif font-bold text-warm-900 tracking-tight leading-tight">
                    {{-- Animated wordmark "BookSoed" --}}
                    <span class="flex items-center justify-center gap-0 text-5xl sm:text-6xl lg:text-7xl mb-3">
                        <span class="hero-letter-b inline-block text-brand-700">B</span>
                        <span class="hero-word-rest inline-block">ook<em class="text-brand-500 not-italic">Soed</em></span>
                    </span>

                    {{-- Tagline under wordmark --}}
                    <span class="hero-tagline block text-3xl sm:text-4xl lg:text-5xl font-normal text-warm-800 italic">
                        Temukan buku bekas layak,<br>
                        <span class="text-brand-700 not-italic font-semibold">jual yang tak terpakai.</span>
                    </span>
                </h1>

                {{-- Subtitle --}}
                <p class="hero-subtitle text-base sm:text-lg text-warm-700 leading-relaxed max-w-2xl mx-auto">
                    Platform khusus mahasiswa Universitas Jenderal Soedirman untuk saling bertukar, membeli,
                    dan menjual buku kuliah bekas secara langsung, aman, dan hemat.
                </p>

                {{-- CTAs --}}
                <div class="hero-cta pt-2 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('books.index') }}" class="btn-primary w-full sm:w-auto text-base px-8 py-3.5">
                        📖 Jelajahi Buku
                    </a>
                    <a href="{{ route('books.create') }}" class="btn-secondary w-full sm:w-auto text-base px-8 py-3.5">
                        ➕ Jual Buku Sekarang
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== HOW IT WORKS ===== --}}
    <div class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-xl mx-auto mb-14">
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-warm-900">Bagaimana BookSoed Bekerja?</h2>
            <p class="text-sm text-warm-700 mt-2">Proses simpel transaksi buku antar sesama mahasiswa di kampus</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Card 1 --}}
            <div class="reveal-card bg-white p-6 rounded-2xl border border-warm-200 shadow-sm
                        hover:border-brand-300 hover:-translate-y-2 hover:shadow-lg
                        transition-all duration-300 cursor-default">
                <div class="w-12 h-12 rounded-xl bg-brand-700 text-white flex items-center justify-center text-lg font-bold mb-4 shadow-sm">1</div>
                <h3 class="font-bold text-warm-900 mb-2">Cari Buku</h3>
                <p class="text-xs text-warm-700 leading-relaxed">Temukan buku kuliah sesuai ketersediaan, mata kuliah, atau kategori yang dibutuhkan.</p>
            </div>

            {{-- Card 2 --}}
            <div class="reveal-card bg-white p-6 rounded-2xl border border-warm-200 shadow-sm
                        hover:border-brand-300 hover:-translate-y-2 hover:shadow-lg
                        transition-all duration-300 cursor-default">
                <div class="w-12 h-12 rounded-xl bg-brand-700 text-white flex items-center justify-center text-lg font-bold mb-4 shadow-sm">2</div>
                <h3 class="font-bold text-warm-900 mb-2">Pesan Langsung</h3>
                <p class="text-xs text-warm-700 leading-relaxed">Buat order langsung ke penjual sesama mahasiswa Unsoed tanpa perantara rumit.</p>
            </div>

            {{-- Card 3 --}}
            <div class="reveal-card bg-white p-6 rounded-2xl border border-warm-200 shadow-sm
                        hover:border-brand-300 hover:-translate-y-2 hover:shadow-lg
                        transition-all duration-300 cursor-default">
                <div class="w-12 h-12 rounded-xl bg-brand-700 text-white flex items-center justify-center text-lg font-bold mb-4 shadow-sm">3</div>
                <h3 class="font-bold text-warm-900 mb-2">Pilih Pembayaran</h3>
                <p class="text-xs text-warm-700 leading-relaxed">Dukungan E-Wallet (GoPay, DANA, OVO, ShopeePay) atau opsi Bayar di Tempat (COD).</p>
            </div>

            {{-- Card 4 --}}
            <div class="reveal-card bg-white p-6 rounded-2xl border border-warm-200 shadow-sm
                        hover:border-brand-300 hover:-translate-y-2 hover:shadow-lg
                        transition-all duration-300 cursor-default">
                <div class="w-12 h-12 rounded-xl bg-brand-700 text-white flex items-center justify-center text-lg font-bold mb-4 shadow-sm">4</div>
                <h3 class="font-bold text-warm-900 mb-2">Serah Terima</h3>
                <p class="text-xs text-warm-700 leading-relaxed">Dapatkan buku kuliah impianmu dengan harga ramah kantong mahasiswa!</p>
            </div>
        </div>
    </div>

</x-app-layout>
