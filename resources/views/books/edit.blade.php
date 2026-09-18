<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between border-b border-warm-200 pb-4">
                <div>
                    <h1 class="font-serif text-2xl font-bold text-warm-900">Edit Listing Buku</h1>
                    <p class="text-xs text-warm-700">Perbarui informasi listing buku {{ $book->title }}</p>
                </div>
                <a href="{{ route('books.my-listings') }}" class="text-xs font-semibold text-brand-700 hover:underline">
                    ← Kembali ke Listing Saya
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8">
                <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    @include('books._form', ['book' => $book])

                    <div class="pt-4 border-t border-warm-200 flex items-center justify-end gap-3">
                        <a href="{{ route('books.my-listings') }}"
                           class="px-4 py-2.5 bg-warm-100 hover:bg-warm-200 text-warm-800 text-xs font-semibold rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white text-xs font-semibold rounded-xl shadow-xs transition duration-150">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
