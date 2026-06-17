@php
    use Statamic\Facades\Entry;

    $tips = Entry::query()
        ->where('collection', 'tips')
        ->whereStatus('published')
        ->get()
        ->sortByDesc(fn ($entry) => $entry->value('updated_at') ?? 0)
        ->values();
@endphp

<x-layout :$andy bottomRight="Tips Dex · {{ $tips->count() }} Notes">
    <x-fieldset.heading :$eyebrow :$subheading :heading="$heading ?? $title" />

    <div class="section">
        @if ($tips->isEmpty())
            <div class="border-2 border-dashed border-neutral-300 bg-stone-50 p-5 font-geist-mono text-xs uppercase tracking-wide text-neutral-500 dark:border-white/10 dark:bg-white/5 dark:text-neutral-400">
                No tips published yet.
            </div>
        @else
            <div class="grid gap-5">
                @foreach ($tips as $tip)
                    <article class="border-2 border-neutral-900 bg-stone-50 p-4 shadow-[4px_4px_0_0_#171717] dark:border-white/15 dark:bg-white/5 dark:shadow-none">
                        <a href="{{ $tip->url() }}" class="group block focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500">
                            <x-atom.subheading class="group-hover:text-orange-700 dark:group-hover:text-orange-400">
                                {{ $tip->get('title') }}
                            </x-atom.subheading>

                            @if (collect($tip->value('tags') ?? [])->isNotEmpty())
                                <x-atom.tags :tags="$tip->value('tags')" class="mt-4" />
                            @endif
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
