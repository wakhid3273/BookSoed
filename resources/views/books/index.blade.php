<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Marketplace Buku — BookSoed
            </h2>
            @auth
                <a href="{{ route('books.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    + Jual Buku
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter --}}
            <form method="GET" action="{{ route('books.index') }}"
                  class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Judul / Penulis..."
                           class="border border-gray-300 rounded-md px-3 py-2 text-sm w-52 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                    <select name="category"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Kondisi</label>
                    <select name="condition"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Semua Kondisi</option>
                        @foreach (\App\Models\Book::conditions() as $key => $label)
                            <option value="{{ $key }}" {{ request('condition') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
                    Cari
                </button>
                @if(request()->hasAny(['search','category','condition']))
                    <a href="{{ route('books.index') }}"
                       class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 underline">Reset</a>
                @endif
            </form>

            {{-- Grid buku --}}
            @if ($books->isEmpty())
                <div class="text-center py-20 text-gray-400">
                    <p class="text-lg">Belum ada buku yang tersedia saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach ($books as $book)
                        <a href="{{ route('books.show', $book) }}"
                           class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden group">
                            {{-- Foto --}}
                            <div class="h-44 bg-gray-100 flex items-center justify-center overflow-hidden">
                                @if ($book->photo_path)
                                    <img src="{{ Storage::url($book->photo_path) }}"
                                         alt="{{ $book->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <span class="text-5xl">📚</span>
                                @endif
                            </div>
                            <div class="p-4">
                                {{-- Status badge --}}
                                <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full mb-2
                                    {{ $book->status === 'AVAILABLE' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ \App\Models\Book::statuses()[$book->status] ?? $book->status }}
                                </span>
                                <p class="font-semibold text-gray-800 text-sm leading-snug line-clamp-2">{{ $book->title }}</p>
                                <p class="text-gray-500 text-xs mt-1">{{ $book->author }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $book->category->name }}</p>
                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-indigo-600 font-bold text-sm">
                                        Rp {{ number_format($book->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Seller: {{ $book->seller->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
