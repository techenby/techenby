@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;
    use Statamic\Facades\Asset;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $updatedAt = is_numeric($entry->value('updated_at')) ? Carbon::createFromTimestamp($entry->value('updated_at')) : null;

    $groups = collect($entry->value('groups') ?? [])
        ->map(fn (array $group) => [
            'heading' => $group['heading'] ?? '',
            'friends' => collect($group['friends'] ?? [])
                ->filter(fn (array $friend) => filled($friend['name'] ?? null))
                ->map(fn (array $friend) => [
                    ...$friend,
                    'avatar' => filled($friend['avatar'] ?? null) ? Asset::find('assets::'.collect($friend['avatar'])->first())?->url() : null,
                    'domain' => filled($friend['url'] ?? null) ? Str::of(parse_url($friend['url'], PHP_URL_HOST) ?? '')->replaceStart('www.', '')->toString() : null,
                ]),
        ])
        ->filter(fn (array $group) => $group['friends']->isNotEmpty());

    $hasFeeds = $groups->flatMap(fn (array $group) => $group['friends'])->contains(fn (array $friend) => filled($friend['rss'] ?? null));
@endphp

<x-layout :$title>
    <div class="space-y-12">
        <div class="flex flex-col gap-6 border-b border-zinc-950/10 pb-7 lg:flex-row lg:items-end lg:justify-between lg:gap-10 dark:border-white/10">
            <x-ui.page-header :heading="$entry->get('title')" :subheading="$entry->get('subheading')" class="max-w-[38.75rem]" />

            @if ($hasFeeds)
                <div class="space-y-2.5 rounded-xl border border-zinc-200 bg-zinc-50 px-5 py-4.5 lg:w-75 lg:shrink-0 dark:border-white/10 dark:bg-white/5">
                    <h2 class="font-display text-lg font-semibold tracking-tight text-zinc-800 dark:text-white">Subscribe to all of them</h2>
                    <p class="text-sm/[1.3125rem] text-zinc-600 dark:text-zinc-300">One file with every friend&rsquo;s RSS feed. Drop it into your feed reader.</p>
                    <a href="/friends.opml" download class="inline-block text-[0.9375rem] font-bold text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">Download friends.opml</a>
                </div>
            @endif
        </div>

        @foreach ($groups as $group)
            <section aria-labelledby="friends-{{ Str::slug($group['heading']) }}" class="space-y-1">
                <h2 id="friends-{{ Str::slug($group['heading']) }}" class="pb-2 font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $group['heading'] }}</h2>

                <ul role="list" class="grid gap-x-10 md:grid-cols-2">
                    @foreach ($group['friends'] as $friend)
                        <li class="flex items-start gap-4 border-t border-zinc-950/10 py-4.5 dark:border-white/10">
                            @if ($friend['avatar'])
                                <img src="{{ $friend['avatar'] }}" alt="" loading="lazy" class="size-13 shrink-0 rounded-full object-cover ring-1 ring-zinc-950/10 ring-offset-2 ring-offset-white dark:ring-white/10 dark:ring-offset-zinc-900">
                            @else
                                <div aria-hidden="true" class="grid size-13 shrink-0 place-items-center rounded-full bg-zinc-200 font-display text-lg font-semibold text-zinc-500 ring-1 ring-zinc-950/10 ring-offset-2 ring-offset-white dark:bg-white/10 dark:text-zinc-300 dark:ring-white/10 dark:ring-offset-zinc-900">{{ Str::substr($friend['name'], 0, 1) }}</div>
                            @endif

                            <div class="min-w-0 grow space-y-1">
                                <div class="flex items-baseline justify-between gap-3">
                                    <h3 class="font-display text-lg/6 font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $friend['name'] }}</h3>

                                    <div class="flex shrink-0 gap-3.5">
                                        @if (filled($friend['url'] ?? null))
                                            <a href="{{ $friend['url'] }}" target="_blank" rel="noopener" class="text-sm font-bold text-zinc-800 underline decoration-enby-yellow decoration-3 underline-offset-4 dark:text-white">Site<span class="sr-only">: {{ $friend['name'] }}</span></a>
                                        @endif
                                        @if (filled($friend['rss'] ?? null))
                                            <a href="{{ $friend['rss'] }}" target="_blank" rel="noopener" class="text-sm font-bold text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-4 dark:text-white">RSS<span class="sr-only">: {{ $friend['name'] }}</span></a>
                                        @endif
                                    </div>
                                </div>

                                @if ($friend['domain'] || filled($friend['pronouns'] ?? null))
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ collect([$friend['domain'], $friend['pronouns'] ?? null])->filter()->implode(' · ') }}</p>
                                @endif

                                @if (filled($friend['note'] ?? null))
                                    <p class="pt-0.5 font-hand text-[1.1875rem]/[1.4375rem] text-pretty text-enby-purple-text">{{ $friend['note'] }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach

        @if (filled($entry->get('outro')))
            <section class="max-w-160 space-y-2">
                @if (filled($entry->get('outro_heading')))
                    <h2 class="font-display text-lg font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $entry->get('outro_heading') }}</h2>
                @endif
                <p class="text-base/[1.625rem] text-pretty text-zinc-600 dark:text-zinc-300">{{ $entry->get('outro') }}</p>
            </section>
        @endif

        <x-ui.site-footer :comms="$andy->comms ?? []" :$updatedAt />
    </div>
</x-layout>
