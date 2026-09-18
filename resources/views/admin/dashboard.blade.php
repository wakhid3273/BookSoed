<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ERP Admin Area') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Selamat Datang di Area Administrasi ERP BookSoed</h3>
                    <p class="text-sm text-gray-600">Halaman ini khusus diakses oleh user dengan role <strong>Admin</strong> untuk mengelola administrasi transaksi dan laporan modul ERP.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
