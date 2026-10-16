@props([
    'entry',
    'badge' => null,
    'description' => null,
    'readingMinutes' => 1,
    'headings' => [],
    'series' => null,
    'comms' => [],
])

@php
    use App\Support\Lately;
    use Illuminate\Support\Carbon;

    $date = app(Lately::class)->date($entry);
    $updatedAt = is_numeric($entry->value('updated_at')) ? Carbon::createFromTimestamp($entry->value('updated_at')) : null;
@endphp

<article class="space-y-10">
    <header class="max-w-[42.5rem] space-y-[1.125rem]">
        @if ($badge)
            @if ($series)
                <x-ui.series-badge>{{ $badge }}</x-ui.series-badge>
            @else
                <flux:badge size="sm">{{ $badge }}</flux:badge>
            @endif
        @endif

        <h1 class="font-display text-[2.25rem]/[2.625rem] font-semibold tracking-tight text-balance text-zinc-800 sm:text-5xl/[3.375rem] dark:text-white">{{ $entry->get('title') }}</h1>

        @if (filled($description))
            <p class="text-xl/8 text-pretty text-zinc-600 dark:text-zinc-300">{{ $description }}</p>
        @endif

        <p class="text-sm text-zinc-500 dark:text-zinc-400">
            @if ($date)
                <time datetime="{{ $date->toDateString() }}">{{ $date->format('M j, Y') }}</time>
            @endif
            @if ($date && $readingMinutes)
                <span aria-hidden="true">·</span>
            @endif
            @if ($readingMinutes)
                {{ $readingMinutes }} min read
            @endif
        </p>
    </header>

    <div class="grid gap-x-16 xl:grid-cols-[minmax(0,42.5rem)_12.5rem]">
        <div class="min-w-0 space-y-12">
            <div id="intro" class="article-prose">
                {{ $slot }}
            </div>

            @if ($series && $series['parts']->isNotEmpty())
                <x-blog.series-nav :$series :current="$entry" />
            @endif

            <x-ui.newsletter />

            <x-ui.more-from-log :except="$entry->id()" />
        </div>

        @if (count($headings) > 1)
            <x-ui.on-this-page :items="[['id' => 'intro', 'text' => 'Intro'], ...$headings]" class="hidden xl:block" />
        @endif
    </div>

    <x-ui.site-footer :$comms :$updatedAt />
</article>
