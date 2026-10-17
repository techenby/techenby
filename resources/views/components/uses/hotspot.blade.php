@props(['item' => false])

@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Term;

    $position = collect(['left', 'top', 'width', 'height'])
        ->map(fn ($field) => $field.': '.$item->value($field).'%;')
        ->implode(' ');

    $type = collect($item->value('types') ?? [])->first();
    $typeTitle = (string) (Term::find('types::'.$type)?->title() ?? Str::of($type ?? '')->replace('-', ' ')->title());

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
    @class([
        'group absolute rounded-lg outline-none ring-2 ring-transparent ring-inset',
        'hover:bg-enby-purple/15 hover:ring-enby-purple focus-visible:bg-enby-purple/15 focus-visible:ring-enby-purple data-selected:bg-enby-purple/15 data-selected:ring-enby-purple' => ! $item->value('action'),
        'hover:bg-enby-yellow/20 hover:ring-enby-yellow focus-visible:bg-enby-yellow/20 focus-visible:ring-enby-yellow data-selected:bg-enby-yellow/20 data-selected:ring-enby-yellow' => $item->value('action'),
    ])
    style="{{ $position }}"
    data-uses-item="{{ $item->slug() }}"
    data-uses-name="{{ $item->value('title') }}"
    data-uses-type="{{ $typeTitle }}"
    data-uses-description="{{ $item->value('content') }}"
    data-uses-links="{{ base64_encode($links->toJson()) }}"
    @if ($item->value('action'))
        data-uses-action="{{ $item->value('action') }}"
    @endif
    aria-label="{{ $item->value('action') ? 'Take a closer look at' : 'Inspect' }} {{ $item->value('title') }}"
>
    <span
        aria-hidden="true"
        @class([
            'absolute top-1/2 left-1/2 size-3 -translate-1/2 rounded-full shadow-sm transition-transform motion-ok:group-hover:scale-125 motion-ok:group-data-selected:scale-125',
            'bg-enby-purple ring-2 ring-white' => ! $item->value('action'),
            'bg-enby-yellow ring-2 ring-zinc-800' => $item->value('action'),
        ])
    ></span>
</button>
