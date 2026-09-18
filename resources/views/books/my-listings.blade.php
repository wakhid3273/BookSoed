<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Listing Buku Saya</h1>
                    <p class="text-sm text-warm-800 mt-1">Kelola katalog buku bekas yang sedang kamu tawarkan di BookSoed</p>
                </div>
                <a href="{{ route('books.create') }}"
                   class="btn-primary shrink-0">
                    <span>+</span> Tambah Listing Baru
                </a>
            </div>

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if ($books->isEmpty())
                <div class="bg-white rounded-2xl border border-warm-200 p-16 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-warm-100 text-warm-400 flex items-center justify-center mx-auto text-3xl">
                        📖
                    </div>
                    <h3 class="font-bold text-warm-900 text-lg">Kamu Belum Punya Listing Buku</h3>
                    <p class="text-sm text-warm-800 max-w-md mx-auto">
                        Punya buku kuliah yang sudah tidak terpakai? Jual sekarang dan bantu sesama mahasiswa Unsoed!
                    </p>
                    <a href="{{ route('books.create') }}"
                       class="inline-block px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-sm rounded-xl transition">
                        + Tambah Listing Sekarang
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-warm-100/70 border-b border-warm-200 text-xs font-semibold text-warm-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">Buku</th>
                                    <th class="px-4 py-3.5">Kategori</th>
                                    <th class="px-4 py-3.5">Kondisi</th>
                                    <th class="px-4 py-3.5 text-right">Harga</th>
                                    <th class="px-4 py-3.5 text-center">Status</th>
                                    <th class="px-6 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-warm-100 text-warm-900">
                                @foreach ($books as $book)
                                    <tr class="hover:bg-warm-50/60 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if ($book->photo_path)
                                                    <img src="{{ Storage::url($book->photo_path) }}"
                                                         class="w-10 h-12 object-cover rounded-lg border border-warm-200 shrink-0" alt="">
                                                @else
                                                    <div class="w-10 h-12 bg-warm-100 rounded-lg border border-warm-200 flex items-center justify-center text-lg shrink-0">📖</div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-warm-900 leading-snug">{{ $book->title }}</p>
                                                    <p class="text-warm-500 text-xs mt-0.5">{{ $book->author }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-xs font-medium text-warm-700">
                                            {{ $book->category->name }}
                                        </td>
                                        <td class="px-4 py-4 text-xs text-warm-700">
                                            {{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}
                                        </td>
                                        <td class="px-4 py-4 text-right font-bold text-brand-700">
                                            Rp {{ number_format($book->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            @include('books._status-badge', ['status' => $book->status])
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('books.show', $book) }}"
                                                   class="px-2.5 py-1.5 bg-warm-100 text-warm-700 hover:bg-warm-200 text-xs font-semibold rounded-lg transition">
                                                    Lihat
                                                </a>
                                                @if ($book->status !== 'SOLD')
                                                    <a href="{{ route('books.edit', $book) }}"
                                                       class="px-2.5 py-1.5 bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 text-xs font-semibold rounded-lg transition">
                                                        Edit
                                                    </a>
                                                @endif
                                                @if ($book->status === 'AVAILABLE' && !$book->orderItems()->exists())
                                                    <form action="{{ route('books.destroy', $book) }}" method="POST"
                                                          onsubmit="return confirm('Yakin ingin menghapus listing buku ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="px-2.5 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-semibold rounded-lg transition">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
