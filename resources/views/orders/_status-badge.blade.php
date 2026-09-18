@php
    $colors = [
        'PENDING'    => 'bg-yellow-100 text-yellow-700',
        'PROCESSING' => 'bg-blue-100 text-blue-700',
        'COMPLETED'  => 'bg-green-100 text-green-700',
        'CANCELLED'  => 'bg-gray-200 text-gray-500',
    ];
    $labels = \App\Models\Order::statuses();
    $color  = $colors[$status] ?? 'bg-gray-100 text-gray-600';
    $label  = $labels[$status] ?? $status;
@endphp
<span class="inline-block text-xs font-semibold px-2 py-1 rounded-full {{ $color }}">
    {{ $label }}
</span>
