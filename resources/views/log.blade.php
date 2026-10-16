@php
    use App\Support\Lately;

    $filters = [
        'writing' => ['label' => 'Writing', 'from' => 'blog'],
        'tips' => ['label' => 'Tips', 'from' => 'tips'],
        'makes' => ['label' => 'Makes', 'from' => 'makes'],
        'moments' => ['label' => 'Moments', 'from' => 'moments'],
    ];

    $activeFilter = array_key_exists((string) request('type'), $filters) ? request('type') : null;
    $from = $activeFilter ? $filters[$activeFilter]['from'] : collect($filters)->pluck('from')->implode('|');
    $lately = app(Lately::class);
@endphp

<x-layout>
    <div class="max-w-4xl space-y-10">
        <header class="space-y-4">
            <h1 class="font-display text-4xl font-semibold tracking-tight text-zinc-800 xl:text-5xl dark:text-white">{{ $title }}</h1>
            @if ((string) $subheading !== '')
                <flux:text class="max-w-[60ch] text-lg text-pretty text-zinc-600 dark:text-zinc-300">{{ $subheading }}</flux:text>
            @endif
        </header>

        <nav aria-label="Filter the log" class="border-b border-zinc-950/10 pb-6 dark:border-white/10">
            <ul role="list" class="flex flex-wrap gap-2">
                <li>
                    <a href="{{ url()->current() }}" @if (! $activeFilter) aria-current="page" @endif @class([
                        'inline-flex rounded-full px-3.5 py-2 text-sm font-semibold',
                        'bg-zinc-800 text-white dark:bg-white dark:text-zinc-900' => ! $activeFilter,
                        'text-zinc-600 ring-1 ring-zinc-950/10 ring-inset hover:bg-zinc-50 dark:text-zinc-300 dark:ring-white/10 dark:hover:bg-white/5' => $activeFilter,
                    ])>All</a>
                </li>
                @foreach ($filters as $key => $filter)
                    <li>
                        <a href="{{ url()->current() }}?type={{ $key }}" @if ($activeFilter === $key) aria-current="page" @endif @class([
                            'inline-flex rounded-full px-3.5 py-2 text-sm font-semibold',
                            'bg-zinc-800 text-white dark:bg-white dark:text-zinc-900' => $activeFilter === $key,
                            'text-zinc-600 ring-1 ring-zinc-950/10 ring-inset hover:bg-zinc-50 dark:text-zinc-300 dark:ring-white/10 dark:hover:bg-white/5' => $activeFilter !== $key,
                        ])>{{ $filter['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <s:collection :from="$from" sort="date:desc" paginate="20" as="entries">
            @php
                $months = collect($entries)->groupBy(fn ($entry) => $entry->date()->format('F Y'));
            @endphp

            @forelse ($months as $month => $monthEntries)
                <section aria-labelledby="log-{{ Str::slug($month) }}">
                    <h2 id="log-{{ Str::slug($month) }}" data-sticky-heading class="sticky top-0 z-10 bg-white font-display text-xl font-semibold tracking-tight text-zinc-800 @container-[scroll-state] lg:-top-10 dark:bg-zinc-800 dark:text-white">
                        <span class="block border-b border-transparent py-3 stuck:border-zinc-950/5 dark:stuck:border-white/10">{{ $month }}</span>
                    </h2>

                    <ol role="list" class="divide-y divide-zinc-950/10 dark:divide-white/10">
                        @foreach ($monthEntries as $entry)
                            <x-ui.entry-row :item="$lately->item($entry)" date-format="M j" />
                        @endforeach
                    </ol>
                </section>
            @empty
                <p class="text-zinc-600 dark:text-zinc-300">Nothing here yet.</p>
            @endforelse

            @if ($paginate['total_pages'] > 1)
                <nav aria-label="Log pages" class="flex justify-between border-t border-zinc-950/10 pt-6 text-sm font-bold dark:border-white/10">
                    <div>
                        @if ($paginate['prev_page'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $paginate['current_page'] - 1]) }}" class="text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">Newer entries</a>
                        @endif
                    </div>
                    <div>
                        @if ($paginate['next_page'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $paginate['current_page'] + 1]) }}" class="text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">Older entries</a>
                        @endif
                    </div>
                </nav>
            @endif
        </s:collection>
    </div>
</x-layout>
