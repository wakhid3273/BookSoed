<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Listing Buku
            </h2>
            <a href="{{ route('books.my-listings') }}" class="text-sm text-indigo-600 hover:underline">← Kembali ke Listing Saya</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow p-8">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf

                    @include('books._form')

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('books.my-listings') }}"
                           class="px-5 py-2 text-sm text-gray-600 border rounded-md hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            Buat Listing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
