@props(['item' => false])

@php
    $position = collect(['left', 'top', 'width', 'height'])
        ->map(fn ($field) => $field.': '.$item->value($field).'%;')
        ->implode(' ');

    $links = collect($item->value('links') ?? [])
        ->filter(fn ($link) => filled($link['label'] ?? null) && filled($link['url'] ?? null))
        ->map(fn ($link) => [
            'label' => $link['label'],
            'url' => $link['url'],
        ])
        ->values();
@endphp

<button
    type="button"
    class="uses-hotspot"
    style="{{ $position }}"
    data-uses-item="{{ $item->slug() }}"
    data-uses-name="{{ $item->value('title') }}"
    data-uses-type="{{ $item->value('item_type') }}"
    data-uses-description="{{ $item->value('content') }}"
    data-uses-links="{{ base64_encode($links->toJson()) }}"
    @if ($item->value('action'))
        data-uses-action="{{ $item->value('action') }}"
    @endif
    aria-label="Inspect {{ $item->value('title') }}"
>
    <span></span>
</button>
