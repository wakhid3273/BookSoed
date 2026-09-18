@props(['status'])

@php
    $classes = match($status) {
        'AVAILABLE' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'RESERVED'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'SOLD'      => 'bg-warm-100 text-warm-600 border-warm-200',
        default     => 'bg-warm-100 text-warm-700 border-warm-200',
    };

    $label = \App\Models\Book::statuses()[$status] ?? $status;
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $classes }}">
    {{ $label }}
</span>
