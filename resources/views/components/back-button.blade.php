@props([
    'href' => null,
    'label' => 'Kembali',
    'fallback' => null
])

@php
    $targetUrl = $href ?? ($fallback ?? url()->previous());
@endphp

<a href="{{ $targetUrl }}"
   {{ $attributes->merge(['class' => 'back-btn group']) }}>
    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 group-hover:-translate-x-0.5"
         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>{{ $label }}</span>
</a>
