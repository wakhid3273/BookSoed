<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Listing Buku Saya
            </h2>
            <a href="{{ route('books.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                + Tambah Listing
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($books->isEmpty())
                <div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
                    <p class="text-5xl mb-4">📚</p>
                    <p class="text-lg">Kamu belum memiliki listing buku.</p>
                    <a href="{{ route('books.create') }}"
                       class="mt-4 inline-block text-indigo-600 hover:underline">Mulai jual buku sekarang →</a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-5 py-3 text-left text-gray-500 font-medium">Buku</th>
                                <th class="px-4 py-3 text-left text-gray-500 font-medium">Kategori</th>
                                <th class="px-4 py-3 text-left text-gray-500 font-medium">Kondisi</th>
                                <th class="px-4 py-3 text-right text-gray-500 font-medium">Harga</th>
                                <th class="px-4 py-3 text-center text-gray-500 font-medium">Status</th>
                                <th class="px-4 py-3 text-center text-gray-500 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($books as $book)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($book->photo_path)
                                                <img src="{{ Storage::url($book->photo_path) }}"
                                                     class="w-10 h-12 object-cover rounded" alt="">
                                            @else
                                                <div class="w-10 h-12 bg-gray-100 rounded flex items-center justify-center text-xl">📖</div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $book->title }}</p>
                                                <p class="text-gray-400 text-xs">{{ $book->author }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-gray-600">{{ $book->category->name }}</td>
                                    <td class="px-4 py-4 text-gray-600">
                                        {{ \App\Models\Book::conditions()[$book->condition] ?? $book->condition }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-semibold text-gray-800">
                                        Rp {{ number_format($book->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-block text-xs font-semibold px-2 py-1 rounded-full
                                            {{ $book->status === 'AVAILABLE' ? 'bg-green-100 text-green-700' :
                                               ($book->status === 'RESERVED' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600') }}">
                                            {{ \App\Models\Book::statuses()[$book->status] ?? $book->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('books.show', $book) }}"
                                               class="text-xs px-3 py-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition">
                                                Lihat
                                            </a>
                                            @if ($book->status !== 'SOLD')
                                                <a href="{{ route('books.edit', $book) }}"
                                                   class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition">
                                                    Edit
                                                </a>
                                            @endif
                                            @if ($book->status === 'AVAILABLE' && !$book->orderItems()->exists())
                                                <form action="{{ route('books.destroy', $book) }}" method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus listing ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-xs px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">
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
                    <div class="px-5 py-4 border-t">
                        {{ $books->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
