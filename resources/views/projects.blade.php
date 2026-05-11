@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Asset;
    use Statamic\Facades\Entry;

    $projects = Entry::query()
        ->where('collection', 'projects')
        ->get()
        ->sortBy(fn ($entry) => $entry->get('title'))
        ->values();
@endphp

<x-layout :$andy bottomRight="Project Dex · {{ $projects->count() }} Stored">
    <x-fieldset.heading :$eyebrow :$subheading :heading="$heading ?? $title" />

    <div class="section">
        @if ($projects->isEmpty())
            <div class="border-2 border-dashed border-neutral-300 bg-stone-50 p-5 font-geist-mono text-xs uppercase tracking-wide text-neutral-500 dark:border-white/10 dark:bg-white/5 dark:text-neutral-400">
                No projects logged yet.
            </div>
        @else
            <div class="grid gap-5">
                @foreach ($projects as $project)
                    @php
                        $headerImage = $project->value('header_image')
                            ? Asset::find('assets::'.$project->value('header_image'))
                            : null;

                        $links = collect($project->value('links') ?? []);
                    @endphp

                    <article class="grid gap-5 border-2 border-neutral-900 bg-stone-50 p-4 shadow-[4px_4px_0_0_#171717] sm:grid-cols-[8rem_1fr] dark:border-white/15 dark:bg-white/5 dark:shadow-none">
                        <div class="grid aspect-square place-items-center bg-white p-4 outline-1 -outline-offset-1 outline-black/10 dark:bg-neutral-950/60 dark:outline-white/10">
                            @if ($headerImage)
                                <img src="{{ $headerImage->url() }}" alt="" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="font-press-start text-[0.625rem] uppercase tracking-wide text-neutral-400 dark:text-neutral-500">No Image</span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <x-atom.subheading>{{ $project->get('title') }}</x-atom.subheading>

                            <div class="mt-4 font-geist text-base text-pretty text-neutral-700 dark:text-neutral-300 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400 [&_p+p]:mt-4">
                                {!! Str::markdown($project->value('content') ?? '') !!}
                            </div>

                            @if ($links->isNotEmpty())
                                <ul role="list" class="mt-5 flex flex-wrap gap-2 border-t-2 border-dashed border-neutral-300 pt-4 dark:border-white/10">
                                    @foreach ($links as $link)
                                        @if (filled($link['url'] ?? null) && filled($link['label'] ?? null))
                                            <li>
                                                <x-atom.button variant="outline" href="{{ $link['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border-2 border-neutral-900 bg-white px-3 py-2 font-geist-mono text-xs font-medium text-neutral-900 shadow-[2px_2px_0_0_#171717] hover:-translate-y-px hover:text-orange-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 dark:border-white/15 dark:bg-neutral-900 dark:text-neutral-100 dark:shadow-none dark:hover:text-orange-400">
                                                    {{ $link['label'] }}
                                                </x-atom.button>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
