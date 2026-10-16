@props(['series', 'current'])

@php
    use App\Support\BlogSeries;
    use Illuminate\Support\Str;

    $partCount = $series['parts']->count();
@endphp

<nav aria-labelledby="series-nav-heading" class="space-y-3.5 rounded-xl border border-zinc-200 bg-zinc-50 p-6 dark:border-white/10 dark:bg-white/5">
    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
        <h2 id="series-nav-heading" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $series['title'] }}</h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">A series · {{ $partCount }} {{ Str::plural('part', $partCount) }} so far</p>
    </div>

    @if ($series['description'] ?? null)
        <p class="text-pretty text-zinc-600 dark:text-zinc-300">{{ $series['description'] }}</p>
    @endif

    <ol role="list" class="space-y-1">
        @foreach ($series['parts'] as $part)
            @php
                $partNumber = app(BlogSeries::class)->part($part) ?? $loop->iteration;
            @endphp

            <li>
                @if ($part->id() === $current->id())
                    <div aria-current="page" class="flex items-center gap-3 rounded-lg border border-zinc-200 bg-white px-3 py-2.5 dark:border-white/10 dark:bg-zinc-800">
                        <span class="w-5 shrink-0 text-sm font-bold text-enby-purple-text">{{ $partNumber }}</span>
                        <span class="grow font-bold text-zinc-800 dark:text-white">{{ $part->get('title') }}</span>
                        <span class="shrink-0 text-[0.8125rem] text-zinc-500 dark:text-zinc-400">you are here</span>
                    </div>
                @else
                    <a href="{{ $part->url() }}" class="group flex items-center gap-3 rounded-lg border border-transparent px-3 py-2.5 hover:border-zinc-200 hover:bg-white dark:hover:border-white/10 dark:hover:bg-zinc-800">
                        <span class="w-5 shrink-0 text-sm font-bold text-zinc-400 dark:text-zinc-500">{{ $partNumber }}</span>
                        <span class="grow font-bold text-zinc-800 decoration-enby-purple decoration-3 underline-offset-5 group-hover:underline dark:text-white">{{ $part->get('title') }}</span>
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
