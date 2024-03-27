@php
    $blobs = [
        'rounded-blob-1',
        'rounded-blob-2',
        'rounded-blob-3',
        'rounded-blob-4',
    ];

    $colors = [
        'bg-blue',
        'bg-java',
        'bg-mardi text-mardi-100 dark:bg-mardi-500',
        'bg-purple text-purple-100 dark:bg-purple-500',
        'bg-yellow',
    ];

    $blob = array_random($blobs);
    $color = array_random($colors);
@endphp

<div {{ $attributes->merge(['class' => "$blob $color"]) }}>
    {{ $slot }}
</div>
