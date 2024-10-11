@php
    $bgColor = match($status) {
        'For review' => 'bg-green-600/80',
        'For approval' => 'bg-yellow-600/80',
        'Rejected' => 'bg-red-600/80',
        default => 'bg-gray-600/80',
    };
@endphp

<span class="px-3 py-1 rounded shadow-sm text-white {{ $bgColor }} whitespace-nowrap">
    {{ ucfirst($status) }}
</span>