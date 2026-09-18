<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookSoed') }} — Marketplace Buku Bekas Unsoed</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-warm-50 text-warm-900 min-h-screen flex flex-col selection:bg-brand-100 selection:text-brand-800">
        @include('layouts.navigation')

        <!-- Optional Page Header -->
        @isset($header)
            <header class="bg-white border-b border-warm-200 py-5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-warm-900 text-warm-300 border-t border-warm-800 py-10 mt-16 text-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-md bg-brand-700 text-white flex items-center justify-center font-bold text-xs">📚</div>
                    <span class="font-serif font-bold text-white text-base">BookSoed</span>
                    <span class="text-xs text-warm-400">· Marketplace Buku Bekas Mahasiswa Unsoed</span>
                </div>

                <div class="flex items-center gap-6 text-xs text-warm-400">
                    <a href="{{ route('books.index') }}" class="hover:text-white transition">Marketplace</a>
                    <a href="{{ route('books.create') }}" class="hover:text-white transition">Jual Buku</a>
                    <span>ERP Module v1.0</span>
                </div>
            </div>
        </footer>
    </body>
</html>
