<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Buku
            </h2>
            <a href="{{ route('books.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Marketplace</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="md:flex">
                    {{-- Foto --}}
                    <div class="md:w-80 bg-gray-100 flex items-center justify-center p-6">
                        @if ($book->photo_path)
                            <img src="{{ Storage::url($book->photo_path) }}"
                                 alt="{{ $book->title }}"
                                 class="max-h-72 object-contain rounded">
                        @else
                            <span class="text-8xl">📚</span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 p-8">
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $book->title }}</h1>
                                <p class="text-gray-500 mt-1">oleh <strong>{{ $book->author }}</strong></p>
                            </div>
                            {{-- Status badge --}}
                            <span class="ml-4 inline-block text-sm font-semibold px-3 py-1 rounded-full
                                {{ $book->status === 'AVAILABLE' ? 'bg-green-100 text-green-700' :
                                   ($book->status === 'RESERVED' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600') }}">
                                {{ \App\Models\Book::statuses()[$book->status] ?? $book->status }}
                            </span>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">Kategori</p>
                                <p class="font-medium text-gray-700">{{ $book->category->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Kondisi</p>
                                <p class="font-medium text-gray-700">{{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}</p>
                            </div>
                            @if ($book->isbn)
                                <div>
                                    <p class="text-gray-400">ISBN</p>
                                    <p class="font-medium text-gray-700">{{ $book->isbn }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-gray-400">Seller</p>
                                <p class="font-medium text-gray-700">{{ $book->seller->name }}</p>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-5">
                            <p class="text-3xl font-bold text-indigo-600">
                                Rp {{ number_format($book->price, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Tombol aksi --}}
                        <div class="mt-6 flex gap-3">
                            @if ($book->status === 'AVAILABLE')
                                {{-- Tombol Order akan ditambahkan pada tahap Order --}}
                                <span class="inline-flex items-center px-5 py-2 bg-gray-100 text-gray-500 text-sm rounded-md cursor-not-allowed">
                                    Order (Segera Hadir)
                                </span>
                            @else
                                <span class="inline-flex items-center px-5 py-2 bg-gray-100 text-gray-400 text-sm rounded-md">
                                    Tidak Tersedia
                                </span>
                            @endif

                            @auth
                                @if (auth()->id() === $book->user_id && $book->status !== 'SOLD')
                                    <a href="{{ route('books.edit', $book) }}"
                                       class="inline-flex items-center px-4 py-2 bg-yellow-400 text-white text-sm rounded-md hover:bg-yellow-500 transition">
                                        Edit Listing
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
