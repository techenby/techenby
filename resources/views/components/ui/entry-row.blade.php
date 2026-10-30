@props(['item', 'dateFormat' => 'M Y'])

@php
    $photos = collect($item['photos'] ?? []);
    $models = $item['models'] ?? [];
    $modal = 'photos-'.$item['id'];
    $series = $item['series']
        ? $item['series'].(isset($item['part']) ? ' · Part '.$item['part'] : '')
        : null;
    $podcast = $item['podcast'] ?? null;
    $podcastMeta = $podcast ? collect([
        $podcast['show'],
        $podcast['number'] ? 'Episode '.$podcast['number'] : null,
        $podcast['minutes'] ? $podcast['minutes'].' min' : null,
    ])->filter()->implode(' · ') : null;
@endphp

<li {{ $attributes->class('grid gap-x-6 gap-y-1 py-5 sm:grid-cols-[7rem_minmax(0,1fr)]') }}>
    <div class="flex flex-wrap items-center gap-2 sm:flex-col sm:items-start">
        <p class="text-sm/5 text-zinc-500 dark:text-zinc-400">
            <span class="font-semibold text-zinc-700 sm:block dark:text-zinc-200">{{ $item['type'] }}</span>
            @if ($item['date'])
                <span aria-hidden="true" class="sm:hidden">·</span>
                <time datetime="{{ $item['date']->toDateString() }}" class="sm:block">{{ $item['date']->format($dateFormat) }}</time>
            @endif
            @if ($item['minutes'] ?? null)
                <span class="sm:hidden"><span aria-hidden="true">·</span> {{ $item['minutes'] }} min read</span>
            @endif
        </p>

        @if ($item['kind'] ?? null)
            <flux:badge size="sm">{{ $item['kind'] }}</flux:badge>
        @endif
    </div>

    @if ($podcast)
        <div class="flex items-center gap-4.5">
            <a href="{{ $podcast['audio'] ?? $item['url'] }}" class="flex size-13 shrink-0 items-center justify-center rounded-full bg-zinc-800 text-white hover:bg-zinc-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-purple dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                <span class="sr-only">Play {{ $item['title'] }}</span>
                <svg aria-hidden="true" viewBox="0 0 18 18" class="size-4.5 fill-current"><path d="M5 3.5v11l9-5.5z" /></svg>
            </a>

            <div class="min-w-0 space-y-1">
                @if ($podcastMeta)
                    <p class="text-sm/5 font-semibold text-zinc-600 dark:text-zinc-300">{{ $podcastMeta }}</p>
                @endif
                <h3 class="font-display text-xl/6.5 font-semibold text-zinc-800 dark:text-white">
                    @if ($item['url'])
                        <flux:link :href="$item['url']" variant="ghost" class="font-semibold! text-inherit hover:decoration-enby-purple">{{ $item['title'] }}</flux:link>
                    @else
                        {{ $item['title'] }}
                    @endif
                </h3>
            </div>
        </div>
    @else
        <div class="space-y-3">
            @if ($photos->isNotEmpty() || $models)
                <x-ui.photo-carousel :$photos :$models :title="$item['title']" :name="$modal" />
            @endif

            <div class="space-y-2">
                @if ($series)
                    <x-ui.series-badge>{{ $series }}</x-ui.series-badge>
                @endif

                <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2">
                    <div class="min-w-0 space-y-1">
                        <h3 class="font-display text-xl font-semibold tracking-[-0.01em] text-zinc-800 sm:text-[1.375rem]/7 dark:text-white">
                            @if ($item['url'])
                                <flux:link :href="$item['url']" variant="ghost" class="font-semibold! text-inherit hover:decoration-enby-purple">{{ $item['title'] }}</flux:link>
                            @else
                                {{ $item['title'] }}
                            @endif
                        </h3>

                        @if ($item['event'] ?? null)
                            <p class="text-base text-zinc-600 dark:text-zinc-300">{{ $item['event'] }}</p>
                        @endif
                    </div>

                    @if ($item['links'] ?? [])
                        <ul role="list" class="flex shrink-0 gap-5 max-sm:order-last max-sm:basis-full">
                            @foreach ($item['links'] as $link)
                                <li><a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="text-[0.9375rem] font-bold text-zinc-800 underline decoration-3 underline-offset-5 dark:text-white {{ $loop->odd ? 'decoration-enby-yellow' : 'decoration-enby-purple' }}">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if ($item['note'] ?? null)
                    <flux:text class="font-hand text-lg/5 text-enby-purple-text">{{ $item['note'] }}</flux:text>
                @endif

                @if ($item['caption'] ?? null)
                    <div class="max-w-[65ch] space-y-3 text-base text-pretty text-zinc-600 dark:text-zinc-300 [&_a]:font-semibold [&_a]:text-zinc-800 [&_a]:underline [&_a]:decoration-enby-purple [&_a]:decoration-[3px] [&_a]:underline-offset-[5px] dark:[&_a]:text-white">
                        {!! $item['caption'] !!}
                    </div>
                @elseif ($item['summary'])
                    <flux:text class="max-w-[65ch] text-base text-pretty text-zinc-600 dark:text-zinc-300">{{ $item['summary'] }}</flux:text>
                @endif

                @if ($item['minutes'] ?? null)
                    <p class="hidden text-sm text-zinc-500 sm:block dark:text-zinc-400">{{ $item['minutes'] }} min read</p>
                @endif
            </div>
        </div>
    @endif
</li>
