<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Monitoring Listing Buku</h1>
                    <p class="text-sm text-warm-600 mt-1">Seluruh listing buku di marketplace BookSoed</p>
                </div>
            </div>

            {{-- Search + Filter --}}
            <form method="GET" action="{{ route('admin.books.index') }}" class="flex flex-wrap gap-2">
                <div class="relative flex-1 min-w-[200px] max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul atau nama seller..."
                           class="w-full pl-4 pr-10 py-2 text-sm border border-warm-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                    @if(request('search'))
                        <a href="{{ route('admin.books.index', request()->except('search')) }}" class="absolute right-3 top-2.5 text-warm-400 hover:text-warm-700">✕</a>
                    @endif
                </div>
                <select name="status"
                        onchange="this.form.submit()"
                        class="py-2 px-3 text-sm border border-warm-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                    <option value="">Semua Status</option>
                    <option value="AVAILABLE" {{ request('status') === 'AVAILABLE' ? 'selected' : '' }}>Tersedia</option>
                    <option value="RESERVED"  {{ request('status') === 'RESERVED'  ? 'selected' : '' }}>Dipesan</option>
                    <option value="SOLD"       {{ request('status') === 'SOLD'      ? 'selected' : '' }}>Terjual</option>
                </select>
                <button type="submit" class="btn-sm-primary px-4 py-2">Cari</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.books.index') }}" class="px-4 py-2 text-xs font-semibold text-warm-700 hover:bg-warm-100 border border-warm-200 rounded-xl transition">Reset</a>
                @endif
            </form>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                @if ($books->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-3xl mb-3">📚</div>
                        <p class="text-sm text-warm-600">Tidak ada listing buku ditemukan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-warm-50 border-b border-warm-200 text-xs font-semibold text-warm-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Buku</th>
                                    <th class="px-4 py-3.5">Seller</th>
                                    <th class="px-4 py-3.5">Kategori</th>
                                    <th class="px-4 py-3.5">Kondisi</th>
                                    <th class="px-4 py-3.5 text-right">Harga</th>
                                    <th class="px-4 py-3.5 text-center">Status</th>
                                    <th class="px-4 py-3.5 text-right">Listing</th>
                                    <th class="px-5 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-warm-100 text-warm-900">
                                @foreach ($books as $book)
                                    <tr class="hover:bg-warm-50 transition">
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-12 rounded-lg bg-warm-100 border border-warm-200 overflow-hidden shrink-0 flex items-center justify-center text-warm-400">
                                                    @if ($book->photo_path)
                                                        <img src="{{ asset('storage/' . $book->photo_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-sm">📚</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-warm-900 text-sm truncate max-w-[180px]">{{ $book->title }}</p>
                                                    <p class="text-xs text-warm-500">{{ $book->author }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-warm-700">{{ $book->seller->name }}</td>
                                        <td class="px-4 py-3.5 text-xs text-warm-600">{{ $book->category->name }}</td>
                                        <td class="px-4 py-3.5 text-xs text-warm-600">{{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}</td>
                                        <td class="px-4 py-3.5 text-right text-xs font-bold text-brand-700">Rp {{ number_format($book->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3.5 text-center">
                                            @php
                                                $statusClass = match($book->status) {
                                                    'AVAILABLE' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'RESERVED'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'SOLD'      => 'bg-warm-100 text-warm-700 border-warm-200',
                                                    default     => 'bg-warm-100 text-warm-700 border-warm-200',
                                                };
                                                $statusLabel = \App\Models\Book::statuses()[$book->status] ?? $book->status;
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-right text-xs text-warm-500">{{ $book->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-3.5 text-center">
                                            <a href="{{ route('admin.books.show', $book) }}"
                                               class="px-2.5 py-1 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-semibold rounded-lg transition-all hover:-translate-y-0.5 inline-block">
                                                Detail →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-warm-100">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
