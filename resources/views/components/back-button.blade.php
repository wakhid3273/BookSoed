@props([
    'href' => null,
    'label' => 'Kembali',
    'fallback' => null
])

@php
    $targetUrl = $href ?? ($fallback ?? url()->previous());
@endphp

<a href="{{ $targetUrl }}"
   {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-xs font-semibold text-warm-700 hover:text-brand-700 bg-white hover:bg-brand-50 border border-warm-200 hover:border-brand-200 px-3 py-1.5 rounded-xl transition duration-150 shadow-2xs group']) }}>
    <svg class="w-3.5 h-3.5 text-warm-500 group-hover:text-brand-700 group-hover:-translate-x-0.5 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>{{ $label }}</span>
</a>
