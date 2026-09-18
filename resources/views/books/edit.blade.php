<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Listing: {{ $book->title }}
            </h2>
            <a href="{{ route('books.my-listings') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Listing Saya</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Warning jika RESERVED --}}
            @if ($book->status === 'RESERVED')
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg text-yellow-800 text-sm">
                    ⚠️ Buku ini sedang dalam status <strong>RESERVED</strong> (sedang diproses dalam transaksi).
                    Harga tidak dapat diubah. Hanya informasi non-kritis yang dapat diedit.
                </div>
            @endif

            <div class="bg-white rounded-xl shadow p-8">
                <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf
                    @method('PUT')

                    @include('books._form', ['book' => $book])

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('books.my-listings') }}"
                           class="px-5 py-2 text-sm text-gray-600 border rounded-md hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
