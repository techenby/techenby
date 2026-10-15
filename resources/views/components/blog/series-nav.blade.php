@props(['series', 'current'])

<nav aria-labelledby="series-nav-heading" class="border-2 border-neutral-900 bg-stone-50 p-4 shadow-[4px_4px_0_0_#171717] dark:border-white/15 dark:bg-white/5 dark:shadow-none">
    <x-atom.eyebrow>In this series</x-atom.eyebrow>

    <h2 id="series-nav-heading" class="mt-2 font-geist font-semibold tracking-tight text-neutral-900 dark:text-neutral-100">
        {{ $series['title'] }}
    </h2>

    <ol role="list" class="mt-4 grid gap-2 font-geist text-sm">
        @foreach ($series['parts'] as $part)
            @php
                $partNumber = app(App\Support\BlogSeries::class)->part($part);
                $isCurrent = $part->id() === $current->id();
            @endphp

            <li class="flex gap-3">
                <span class="w-14 shrink-0 font-geist-mono text-[0.6875rem] uppercase tracking-wide leading-5 text-neutral-500 dark:text-neutral-400">
                    @isset($partNumber)
                        Part {{ $partNumber }}
                    @endisset
                </span>

                @if ($isCurrent)
                    <span aria-current="page" class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $part->get('title') }}</span>
                @else
                    <a href="{{ $part->url() }}" class="text-neutral-700 underline decoration-neutral-300 underline-offset-4 hover:text-orange-700 hover:decoration-orange-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 dark:text-neutral-300 dark:decoration-white/20 dark:hover:text-orange-400">{{ $part->get('title') }}</a>
                @endif
            </li>
        @endforeach
    </ol>

    @if ($series['previous'] || $series['next'])
        <div class="mt-4 flex justify-between gap-4 border-t-2 border-dashed border-neutral-300 pt-4 dark:border-white/10">
            <div>
                @if ($series['previous'])
                    <x-atom.button variant="ghost" size="sm" :href="$series['previous']->url()">&larr; Previous</x-atom.button>
                @endif
            </div>
            <div>
                @if ($series['next'])
                    <x-atom.button variant="ghost" size="sm" :href="$series['next']->url()">Next &rarr;</x-atom.button>
                @endif
            </div>
        </div>
    @endif
</nav>
