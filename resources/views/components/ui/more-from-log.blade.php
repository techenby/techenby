@props(['except' => null])

@php
    use App\Support\Lately;

    $items = app(Lately::class)
        ->items(array_keys(Lately::TYPES), 4)
        ->reject(fn (array $item) => $item['id'] === $except)
        ->take(3);
@endphp

@if ($items->isNotEmpty())
    <section aria-labelledby="more-from-the-log" class="space-y-2 pt-2">
        <div class="flex items-baseline justify-between gap-4 pb-1.5">
            <h2 id="more-from-the-log" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">More from the Log</h2>
            <a href="/log" class="text-[0.9375rem] font-bold text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">The whole log</a>
        </div>

        <ul role="list" class="divide-y divide-zinc-950/10 border-y border-zinc-950/10 dark:divide-white/10 dark:border-white/10">
            @foreach ($items as $item)
                <li class="flex items-center gap-4 py-3.5">
                    @if ($item['podcast'] ?? null)
                        <a href="{{ $item['podcast']['audio'] ?? $item['url'] }}" class="flex size-14 shrink-0 items-center justify-center rounded-full bg-zinc-800 text-white hover:bg-zinc-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-purple dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                            <span class="sr-only">Play {{ $item['title'] }}</span>
                            <svg aria-hidden="true" viewBox="0 0 18 18" class="size-4.5 fill-current"><path d="M5 3.5v11l9-5.5z" /></svg>
                        </a>
                    @elseif ($item['photos'] !== [])
                        <img src="{{ $item['photos'][0]['square'] }}" alt="" loading="lazy" class="size-14 shrink-0 rounded-md object-cover ring-1 ring-zinc-950/10 dark:ring-white/10">
                    @else
                        <div aria-hidden="true" class="size-14 shrink-0"></div>
                    @endif

                    <div class="min-w-0 space-y-0.5">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $item['type'] }}
                            @if ($item['date'])
                                <span aria-hidden="true">·</span>
                                <time datetime="{{ $item['date']->toDateString() }}">{{ $item['date']->format('M j') }}</time>
                            @endif
                        </p>
                        <h3 class="font-display text-lg/6 font-semibold tracking-tight text-zinc-800 dark:text-white">
                            @if ($item['url'])
                                <flux:link :href="$item['url']" variant="ghost" class="font-semibold! text-inherit hover:decoration-enby-purple">{{ $item['title'] }}</flux:link>
                            @else
                                {{ $item['title'] }}
                            @endif
                        </h3>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif
