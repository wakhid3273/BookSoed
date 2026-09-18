<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookSoed') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-warm-50 text-warm-900 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 selection:bg-brand-100 selection:text-brand-800">

        <div class="mb-6 text-center">
            <a href="/" class="inline-block transition hover:opacity-90">
                <x-application-logo class="h-10 w-auto" />
            </a>
            <p class="text-xs text-warm-600 mt-2">Marketplace Buku Bekas Mahasiswa Unsoed</p>
        </div>

        <div class="w-full sm:max-w-md bg-white border border-warm-200 shadow-md rounded-2xl p-6 sm:p-8">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center text-xs text-warm-400">
            &copy; {{ date('Y') }} BookSoed ERP. All rights reserved.
        </div>

    </body>
</html>
