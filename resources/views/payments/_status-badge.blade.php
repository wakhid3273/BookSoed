@props(['status'])

@php
    $classes = match($status) {
        'PAID'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'PENDING' => 'bg-amber-50 text-amber-700 border-amber-200',
        'FAILED'  => 'bg-rose-50 text-rose-700 border-rose-200',
        default   => 'bg-warm-100 text-warm-700 border-warm-200',
    };

    $label = match($status) {
        'PAID'    => 'LUNAS',
        'PENDING' => 'MENUNGGU PEMBAYARAN',
        'FAILED'  => 'GAGAL',
        default   => $status,
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $classes }}">
    {{ $label }}
</span>
