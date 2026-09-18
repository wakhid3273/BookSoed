<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Back button at top --}}
            <x-back-button :href="route('books.my-listings')" label="← Kembali ke Listing Saya" />

            <div class="border-b border-warm-200 pb-4">
                <h1 class="font-serif text-2xl font-bold text-warm-900">Tambah Listing Buku</h1>
                <p class="text-xs text-warm-700 mt-1">Tawarkan buku kuliah bekas milikmu ke sesama mahasiswa Unsoed</p>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-sm p-6 sm:p-8">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    @include('books._form')

                    <div class="pt-4 border-t border-warm-200 flex items-center justify-end gap-3">
                        <a href="{{ route('books.my-listings') }}"
                           class="px-4 py-2.5 bg-warm-100 hover:bg-warm-200 text-warm-800 text-xs font-semibold rounded-xl transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" class="btn-sm-primary px-6 py-2.5 text-sm">
                            + Pasang Listing Buku
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
