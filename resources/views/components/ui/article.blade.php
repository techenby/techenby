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
    $headingIds = collect($headings)->pluck('id')->all();
@endphp

<article class="space-y-10">
    <header class="max-w-[42.5rem] space-y-[1.125rem]">
        @if ($badge)
            <flux:badge size="sm">{{ $badge }}</flux:badge>
        @endif

        <h1 class="font-display text-[2.25rem]/[2.625rem] font-semibold tracking-tight text-balance text-zinc-800 sm:text-5xl/[3.375rem] dark:text-white">{{ $entry->get('title') }}</h1>

        @if (filled($description))
            <p class="text-xl/8 text-pretty text-zinc-600 dark:text-zinc-300">{{ $description }}</p>
        @endif

        <p class="text-sm text-zinc-500 dark:text-zinc-400">
            @if ($date)
                <time datetime="{{ $date->toDateString() }}">{{ $date->format('M j, Y') }}</time>
                <span aria-hidden="true">·</span>
            @endif
            {{ $readingMinutes }} min read
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

            <x-ui.more-from-log :except="$entry->id()" />
        </div>

        @if (count($headings) > 1)
            <nav aria-labelledby="on-this-page" class="hidden xl:block">
                <div
                    class="sticky top-0"
                    x-data="{
                        active: 'intro',
                        init() {
                            const targets = @js($headingIds).map((id) => document.getElementById(id)).filter(Boolean)
                            let queued = false
                            const update = () => {
                                queued = false
                                this.active = targets.filter((target) => target.getBoundingClientRect().top <= 120).at(-1)?.id ?? 'intro'
                            }

                            {{-- Scroll events don't bubble, so listen during capture to hear the main panel scroll too. --}}
                            window.addEventListener('scroll', () => {
                                if (! queued) {
                                    queued = true
                                    requestAnimationFrame(update)
                                }
                            }, { capture: true, passive: true })

                            update()
                        },
                    }"
                >
                    <h2 id="on-this-page" class="px-2.5 pb-2.5 text-sm font-semibold text-zinc-600 dark:text-zinc-300">On this page</h2>

                    <ul role="list" class="space-y-0.5">
                        @foreach ([['id' => 'intro', 'text' => 'Intro'], ...$headings] as $heading)
                            <li>
                                <a
                                    href="#{{ $heading['id'] }}"
                                    x-bind:aria-current="active === @js($heading['id']) ? 'location' : false"
                                    class="block rounded-md px-2.5 py-1.5 text-[0.9375rem]/5 font-medium text-pretty text-zinc-600 hover:text-zinc-800 aria-[current]:bg-zinc-100 aria-[current]:font-bold aria-[current]:text-zinc-800 dark:text-zinc-300 dark:hover:text-white dark:aria-[current]:bg-white/5 dark:aria-[current]:text-white"
                                >{{ $heading['text'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        @endif
    </div>

    <x-ui.site-footer :$comms :$updatedAt />
</article>
