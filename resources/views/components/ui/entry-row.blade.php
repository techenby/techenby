@props(['item', 'dateFormat' => 'M Y'])

@php
    $photos = collect($item['photos'] ?? []);
    $modal = 'photos-'.$item['id'];
    $badge = $item['series']
        ? $item['series'].(isset($item['part']) ? ' · Part '.$item['part'] : '')
        : ($item['kind'] ?? null);
@endphp

<li {{ $attributes->class('grid gap-x-6 gap-y-1 py-5 sm:grid-cols-[7rem_minmax(0,1fr)]') }}>
    <flux:text size="sm">
        {{ $item['type'] }}
        @if ($item['date'])
            <span aria-hidden="true">·</span>
            <time datetime="{{ $item['date']->toDateString() }}">{{ $item['date']->format($dateFormat) }}</time>
        @endif
    </flux:text>

    <div class="space-y-3">
        @if ($photos->isNotEmpty())
            <flux:carousel advance="page" arrows:position="overlap" aria-label="Photos of {{ $item['title'] }}">
                @foreach ($photos as $photo)
                    <flux:carousel.slide class="w-1/3 sm:w-1/4 lg:w-1/5">
                        <flux:modal.trigger :name="$modal">
                            <button
                                type="button"
                                x-on:click="setTimeout(() => document.getElementById('{{ $modal }}-{{ $loop->index }}')?.scrollIntoView({ block: 'start' }), 100)"
                                class="block w-full rounded-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-purple"
                            >
                                <span class="sr-only">View larger:</span>
                                <img src="{{ $photo['square'] }}" alt="{{ $photo['alt'] }}" loading="lazy" class="aspect-square w-full rounded-lg object-cover ring-1 ring-zinc-950/10 dark:ring-white/10">
                            </button>
                        </flux:modal.trigger>
                    </flux:carousel.slide>
                @endforeach
            </flux:carousel>
        @endif

        <div class="space-y-2">
            @if ($badge)
                <flux:badge size="sm">{{ $badge }}</flux:badge>
            @endif

            <h3 class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">
                @if ($item['url'])
                    <flux:link :href="$item['url']" variant="ghost" class="text-inherit hover:decoration-enby-purple">{{ $item['title'] }}</flux:link>
                @else
                    {{ $item['title'] }}
                @endif
            </h3>

            @if ($item['note'] ?? null)
                <p class="font-hand text-lg/5 text-enby-purple-text">{{ $item['note'] }}</p>
            @endif

            @if ($item['summary'])
                <flux:text class="max-w-[65ch] text-base text-pretty text-zinc-600 dark:text-zinc-300">{{ $item['summary'] }}</flux:text>
            @endif
        </div>
    </div>

    @if ($photos->isNotEmpty())
        <flux:modal :name="$modal" scroll="body" class="w-full max-w-4xl">
            <div class="space-y-6">
                <flux:heading size="lg" level="2" class="font-display">{{ $item['title'] }}</flux:heading>

                @foreach ($photos as $photo)
                    <figure id="{{ $modal }}-{{ $loop->index }}" class="scroll-mt-6 space-y-2">
                        <img src="{{ $photo['full'] }}" alt="" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" loading="lazy" class="mx-auto h-auto max-h-[85dvh] w-auto max-w-full rounded-lg">
                        <figcaption class="text-sm text-zinc-600 dark:text-zinc-300">{{ $photo['alt'] }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </flux:modal>
    @endif
</li>
