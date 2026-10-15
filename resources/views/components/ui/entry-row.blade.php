@props(['item', 'dateFormat' => 'M Y'])

<li {{ $attributes->class('grid gap-x-6 gap-y-1 py-5 sm:grid-cols-[7rem_minmax(0,1fr)]') }}>
    <p class="text-sm text-zinc-500 dark:text-zinc-400">
        {{ $item['type'] }}
        @if ($item['date'])
            <span aria-hidden="true">·</span>
            <time datetime="{{ $item['date']->toDateString() }}">{{ $item['date']->format($dateFormat) }}</time>
        @endif
    </p>

    <div class="space-y-2">
        @if ($item['series'])
            <p class="inline-flex rounded-md bg-zinc-50 px-2 py-0.5 text-sm font-bold text-zinc-600 ring-1 ring-zinc-950/10 ring-inset dark:bg-white/5 dark:text-zinc-300 dark:ring-white/10">
                {{ $item['series'] }}@isset($item['part']) · Part {{ $item['part'] }}@endisset
            </p>
        @endif

        <h3 class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">
            <a href="{{ $item['url'] }}" class="hover:underline hover:decoration-enby-purple hover:decoration-2 hover:underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-enby-purple">{{ $item['title'] }}</a>
        </h3>

        @if ($item['summary'])
            <p class="max-w-[65ch] text-base text-pretty text-zinc-600 dark:text-zinc-300">{{ $item['summary'] }}</p>
        @endif
    </div>
</li>
