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
                                x-on:click="setTimeout(() => document.getElementById('{{ $modal }}-{{ $loop->index }}')?.scrollIntoView({ block: 'nearest', inline: 'start', behavior: 'instant' }), 50)"
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
                <flux:text class="font-hand text-lg/5 text-enby-purple-text">{{ $item['note'] }}</flux:text>
            @endif

            @if ($item['caption'] ?? null)
                <flux:text class="max-w-[65ch] space-y-3 text-base text-pretty text-zinc-600 dark:text-zinc-300 [&_a]:font-semibold [&_a]:text-zinc-800 [&_a]:underline [&_a]:decoration-enby-purple [&_a]:decoration-[3px] [&_a]:underline-offset-[5px] dark:[&_a]:text-white">
                    {!! $item['caption'] !!}
                </flux:text>
            @elseif ($item['summary'])
                <flux:text class="max-w-[65ch] text-base text-pretty text-zinc-600 dark:text-zinc-300">{{ $item['summary'] }}</flux:text>
            @endif
        </div>
    </div>

    @if ($photos->isNotEmpty())
        <flux:modal :name="$modal" variant="bare" class="m-0 h-dvh max-h-none w-full max-w-none overflow-hidden p-0 text-white backdrop:bg-zinc-950/95!">
            <div
                class="flex h-full flex-col"
                x-on:keydown.right="$el.querySelector('ui-carousel').next()"
                x-on:keydown.left="$el.querySelector('ui-carousel').previous()"
            >
                <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6">
                    <h2 class="font-display text-lg font-semibold tracking-tight">{{ $item['title'] }}</h2>

                    <flux:modal.close>
                        <flux:button variant="ghost" icon="x-mark" aria-label="Close photos" class="text-white! hover:bg-white/10!" />
                    </flux:modal.close>
                </div>

                <flux:carousel snap="mandatory" aria-label="Photos of {{ $item['title'] }}" :arrows="$photos->count() > 1" arrows:class="max-sm:hidden" class="min-h-0 flex-1 [&>[data-flux-carousel-viewport]]:h-full" track:class="h-full gap-0">
                    @foreach ($photos as $photo)
                        <flux:carousel.slide id="{{ $modal }}-{{ $loop->index }}" class="w-full">
                            <figure
                                class="flex h-full flex-col items-center justify-center gap-4 px-4 pb-6 sm:px-16"
                                x-on:click.self="$flux.modal('{{ $modal }}').close()"
                            >
                                <img src="{{ $photo['full'] }}" alt="" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" loading="lazy" class="min-h-0 w-auto max-w-full flex-1 rounded-lg object-contain">
                                <figcaption class="max-w-prose text-center text-sm text-pretty text-zinc-300">
                                    @if ($photos->count() > 1)
                                        <span class="font-semibold text-white">{{ $loop->iteration }} of {{ $photos->count() }}</span>
                                        <span aria-hidden="true" class="text-zinc-500">·</span>
                                    @endif
                                    {{ $photo['alt'] }}
                                </figcaption>
                            </figure>
                        </flux:carousel.slide>
                    @endforeach
                </flux:carousel>
            </div>
        </flux:modal>
    @endif
</li>
