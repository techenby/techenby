@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;
    use Statamic\Facades\Asset;
    use Statamic\Facades\Entry;
    use Statamic\Modifiers\Modify;

    $entry = Entry::find($id);
    $entryTitle = $entry?->get('title') ?? $title;
    $entryContent = $entry?->value('content') ?? [];
    $tags = collect($entry?->value('tags') ?? []);
    $updatedAt = $entry?->value('updated_at')
        ? Carbon::createFromTimestamp($entry->value('updated_at'))->format('M j, Y')
        : null;

    $headerImages = collect($entry?->value('header_image') ?? [])
        ->map(fn ($path) => Asset::find('assets::'.$path))
        ->filter()
        ->values();

    $lightHeaderImage = $headerImages->first(fn ($asset) => Str::contains($asset->path(), '-light')) ?? $headerImages->first();
    $darkHeaderImage = $headerImages->first(fn ($asset) => Str::contains($asset->path(), '-dark')) ?? $headerImages->first();
    $hasModeHeaderImages = $headerImages->count() > 1;
    $contentHtml = Modify::value($entryContent)->bardHtml()->fetch();
    $contentText = Modify::value($entryContent)->bardText()->fetch();
    $readingMinutes = max(1, (int) ceil(str_word_count($contentText) / 225));
@endphp

<x-layout :$andy bottomRight="Blog Entry · {{ $readingMinutes }} Min">
    <article class="min-w-0 space-y-8">
        <header>
            <x-atom.button variant="ghost" size="sm" href="/blog">
                &larr; Blog
            </x-atom.button>

            <h1 class="mt-8 max-w-[21ch] font-instrument-serif text-5xl tracking-tight text-pretty text-neutral-900 sm:text-6xl dark:text-neutral-100">
                {{ $entryTitle }}
            </h1>

            <div class="mt-6 flex flex-wrap items-center gap-x-3 gap-y-2 border-y-2 border-dashed border-neutral-300 py-4 font-geist-mono text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:border-white/10 dark:text-neutral-400">
                @if ($updatedAt)
                    <time datetime="{{ Carbon::createFromTimestamp($entry->value('updated_at'))->toDateString() }}">{{ $updatedAt }}</time>
                    <span aria-hidden="true" class="text-orange-700 dark:text-orange-400">/</span>
                @endif

                <span>{{ $readingMinutes }} min read</span>

                @if ($tags->isNotEmpty())
                    <span aria-hidden="true" class="text-orange-700 dark:text-orange-400">/</span>
                    <x-atom.tags :$tags />
                @endif
            </div>
        </header>

        @if ($headerImages->isNotEmpty())
            <figure class="relative w-full bg-white p-2 outline-2 outline-neutral-900 shadow-[6px_6px_0_0_#171717] dark:bg-neutral-950 dark:outline-white/15 dark:shadow-none">
                @if ($hasModeHeaderImages)
                    <img src="{{ $lightHeaderImage->url() }}" alt="" class="w-full bg-white object-contain dark:hidden">
                    <img src="{{ $darkHeaderImage->url() }}" alt="" class="hidden w-full bg-neutral-950 object-contain dark:block">
                @else
                    <img src="{{ $headerImages->first()->url() }}" alt="" class="w-full object-contain">
                @endif
            </figure>
        @endif

        <div class="blog-prose">
            {!! $contentHtml !!}
        </div>
    </article>
</x-layout>
