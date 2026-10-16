@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $asOf = $entry->value('as_of') ? Carbon::parse($entry->value('as_of')) : null;
    $tldr = collect($entry->value('tldr') ?? [])->filter();

    $sections = collect($entry->value('sections') ?? [])
        ->filter(fn (array $section) => $section['enabled'] ?? true)
        ->map(function (array $section) {
            $label = ($section['toc_label'] ?? null) ?: (($section['heading'] ?? null) ?: "Do and don't");

            return [...$section, 'label' => $label, 'anchor' => Str::slug($label)];
        })
        ->values();

    $toc = collect($tldr->isNotEmpty() ? [['id' => 'the-20-second-version', 'text' => 'The 20-second version']] : [])
        ->concat($sections->map(fn (array $section) => ['id' => $section['anchor'], 'text' => $section['label']]))
        ->all();

    $isPrompt = fn (?string $text) => Str::startsWith(trim((string) $text), 'Prompt:');
@endphp

<x-layout :$title :updated-at="$entry->value('updated_at')">
    <div class="space-y-12">
        <div class="grid gap-x-16 xl:grid-cols-[minmax(0,42.5rem)_12.5rem]">
            <div class="min-w-0 space-y-10">
                <x-ui.page-header
                    :heading="$entry->get('title')"
                    :subheading="$entry->get('subheading')"
                    :eyebrow="$asOf ? 'As of '.$asOf->format('F Y').'. People change, and so will this page.' : null"
                />

                @if ($tldr->isNotEmpty())
                    <section id="the-20-second-version" aria-labelledby="the-20-second-version-heading" class="scroll-mt-6 space-y-3.5 rounded-xl border border-zinc-200 bg-zinc-50 p-6 dark:border-white/10 dark:bg-white/5">
                        <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                            <h2 id="the-20-second-version-heading" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">The 20-second version</h2>
                            <p class="-rotate-[1.5deg] font-hand text-[1.1875rem]/[1.375rem] text-enby-purple-text">if you read nothing else</p>
                        </div>

                        <ul role="list" class="space-y-2.5">
                            @foreach ($tldr as $line)
                                <li class="flex items-baseline gap-3">
                                    <span aria-hidden="true" class="size-1.5 shrink-0 -translate-y-0.5 rounded-full bg-enby-purple"></span>
                                    <span @class([
                                        'text-[1.0625rem]/[1.625rem] text-pretty',
                                        'text-zinc-500 italic dark:text-zinc-400' => $isPrompt($line),
                                        'text-zinc-700 dark:text-zinc-300' => ! $isPrompt($line),
                                    ])>{{ $line }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @foreach ($sections as $section)
                    <section id="{{ $section['anchor'] }}" class="scroll-mt-6 space-y-3 border-t border-zinc-950/10 pt-6 dark:border-white/10">
                        @if ($section['type'] === 'do_dont')
                            <h2 class="sr-only">{{ $section['label'] }}</h2>

                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach (['do' => 'Please do', 'dont' => "Please don't"] as $key => $heading)
                                    <div class="space-y-3 rounded-xl border border-zinc-200 bg-zinc-50 p-5 dark:border-white/10 dark:bg-white/5">
                                        <h3 class="font-display text-lg font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $heading }}</h3>

                                        <ul role="list" class="space-y-3">
                                            @foreach (collect($section[$key] ?? [])->filter() as $line)
                                                <li @class([
                                                    'border-l-3 pl-3 text-base/[1.5625rem] text-pretty',
                                                    'border-enby-purple' => $key === 'do',
                                                    'border-zinc-200 dark:border-white/15' => $key === 'dont',
                                                    'text-zinc-500 italic dark:text-zinc-400' => $isPrompt($line),
                                                    'text-zinc-700 dark:text-zinc-300' => ! $isPrompt($line),
                                                ])>{{ $line }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <h2 class="font-display text-2xl/[1.875rem] font-semibold tracking-tight text-balance text-zinc-800 dark:text-white">{{ $section['heading'] }}</h2>

                            @if ($section['type'] === 'topics' && filled($section['topics'] ?? null))
                                <ul role="list" class="flex flex-wrap gap-2 pt-1">
                                    @foreach (collect($section['topics'])->filter() as $topic)
                                        <li class="rounded-full px-3 py-1.5 text-[0.9375rem]/5 font-semibold text-zinc-600 ring-1 ring-zinc-950/10 ring-inset dark:text-zinc-300 dark:ring-white/10">{{ $topic }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <x-ui.answer :text="$section['content'] ?? ''" />
                        @endif
                    </section>
                @endforeach
            </div>

            @if (count($toc) > 1)
                <x-ui.on-this-page :items="$toc" class="hidden pt-32 xl:block" />
            @endif
        </div>
    </div>
</x-layout>
