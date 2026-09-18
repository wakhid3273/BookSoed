<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Header Marketplace --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Marketplace Buku Bekas</h1>
                    <p class="text-sm text-warm-800 mt-1">Cari dan temukan buku bekas berkualitas dari sesama mahasiswa Unsoed</p>
                </div>
                @auth
                    <a href="{{ route('books.create') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-xs transition duration-150 shrink-0">
                        <span>+</span> Jual Buku Kamu
                    </a>
                @endauth
            </div>

            {{-- Filter & Search Form --}}
            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-5">
                <form method="GET" action="{{ route('books.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-warm-800 mb-1.5">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Judul buku atau nama penulis..."
                                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 placeholder-warm-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-warm-800 mb-1.5">Kategori</label>
                        <select name="category"
                                class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-warm-800 mb-1.5">Kondisi Buku</label>
                        <select name="condition"
                                class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                            <option value="">Semua Kondisi</option>
                            @foreach (\App\Models\Book::conditions() as $key => $label)
                                <option value="{{ $key }}" {{ request('condition') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="flex-1 px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white text-sm font-semibold rounded-xl transition duration-150">
                            Cari
                        </button>
                        @if(request()->hasAny(['search','category','condition']))
                            <a href="{{ route('books.index') }}"
                               class="px-3 py-2.5 text-xs font-medium text-warm-600 hover:text-warm-900 bg-warm-100 hover:bg-warm-200 rounded-xl transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Grid Buku --}}
            @if ($books->isEmpty())
                <div class="bg-white rounded-2xl border border-warm-200 p-16 text-center">
                    <div class="w-16 h-16 rounded-full bg-warm-100 text-warm-400 flex items-center justify-center mx-auto text-3xl mb-4">
                        📚
                    </div>
                    <h3 class="font-bold text-warm-900 text-lg">Belum Ada Buku Ditemukan</h3>
                    <p class="text-sm text-warm-800 mt-1 max-w-md mx-auto">
                        @if(request()->hasAny(['search','category','condition']))
                            Coba ubah kata kunci atau filter pencarian kamu untuk menemukan buku lainnya.
                        @else
                            Belum ada buku yang didaftarkan di marketplace saat ini.
                        @endif
                    </p>
                    @auth
                        <a href="{{ route('books.create') }}" class="inline-block mt-5 px-5 py-2.5 bg-brand-700 text-white font-semibold text-sm rounded-xl hover:bg-brand-800 transition">
                            Jadilah yang Pertama Menjual Buku!
                        </a>
                    @endauth
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($books as $book)
                        <a href="{{ route('books.show', $book) }}"
                           class="group bg-white rounded-2xl border border-warm-200 hover:border-brand-300 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden">
                            
                            {{-- Cover / Photo --}}
                            <div class="h-52 bg-warm-100 flex items-center justify-center overflow-hidden relative border-b border-warm-100">
                                @if ($book->photo_path)
                                    <img src="{{ Storage::url($book->photo_path) }}"
                                         alt="{{ $book->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="flex flex-col items-center justify-center text-warm-400">
                                        <span class="text-4xl mb-1">📘</span>
                                        <span class="text-[11px] font-mono tracking-wider uppercase">BookSoed</span>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3">
                                    @include('books._status-badge', ['status' => $book->status])
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-[11px] font-semibold text-brand-700 tracking-wider uppercase mb-1">
                                        {{ $book->category->name }}
                                    </div>
                                    <h3 class="font-bold text-warm-900 text-sm leading-snug line-clamp-2 group-hover:text-brand-700 transition">
                                        {{ $book->title }}
                                    </h3>
                                    <p class="text-xs text-warm-800 mt-1 line-clamp-1">
                                        Oleh: {{ $book->author }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-warm-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-warm-400 block">Harga</span>
                                        <span class="font-bold text-brand-700 text-base">
                                            Rp {{ number_format($book->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <span class="text-xs px-2.5 py-1 bg-warm-100 text-warm-700 font-medium rounded-md">
                                        {{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}
                                    </span>
                                </div>
                            </div>

                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
