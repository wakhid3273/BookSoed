@props(['status'])

@php
    $classes = match($status) {
        'PAID' => 'bg-green-100 text-green-800',
        'PENDING' => 'bg-yellow-100 text-yellow-800',
        'FAILED' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800',
    };

    $label = match($status) {
        'PAID' => 'LUNAS',
        'PENDING' => 'MENUNGGU PEMBAYARAN',
        'FAILED' => 'GAGAL',
        default => $status,
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $classes }}">
    {{ $label }}
</span>
