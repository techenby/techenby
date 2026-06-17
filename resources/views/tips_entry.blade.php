@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $entryTitle = $entry?->get('title') ?? $title;
    $entryContent = $entry?->value('content') ?? '';
    $tags = collect($entry?->value('tags') ?? []);
    $updatedAt = $entry?->value('updated_at')
        ? Carbon::createFromTimestamp($entry->value('updated_at'))->format('M j, Y')
        : null;
@endphp

<x-layout :$andy bottomRight="Tips & Tricks">
    <article class="min-w-0 space-y-8">
        <header>
            <x-atom.button variant="ghost" size="sm" href="/tips">
                &larr; Tips & Tricks
            </x-atom.button>

            <h1 class="mt-8 max-w-[21ch] font-instrument-serif text-5xl tracking-tight text-pretty text-neutral-900 sm:text-6xl dark:text-neutral-100">
                {{ $entryTitle }}
            </h1>

            <div class="mt-6 flex flex-wrap items-center gap-x-3 gap-y-2 border-y-2 border-dashed border-neutral-300 py-4 font-geist-mono text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:border-white/10 dark:text-neutral-400">
                @if ($updatedAt)
                    <time datetime="{{ Carbon::createFromTimestamp($entry->value('updated_at'))->toDateString() }}">{{ $updatedAt }}</time>
                @endif

                @if ($updatedAt && $tags->isNotEmpty())
                    <span aria-hidden="true" class="text-orange-700 dark:text-orange-400">/</span>
                @endif

                @if ($tags->isNotEmpty())
                    <x-atom.tags :$tags />
                @endif
            </div>
        </header>

        <div class="blog-prose">
            {!! Str::markdown($entryContent) !!}
        </div>
    </article>
</x-layout>
