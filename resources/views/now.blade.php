@php
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $asOf = $entry->augmentedValue('as_of')->value();
    $sections = collect($entry->value('sections') ?? [])->filter(fn (array $section) => filled($section['heading'] ?? null));
@endphp

<x-layout :$title :updated-at="$entry->lastModified()">
    <div class="max-w-4xl space-y-12">
        <x-ui.page-header
            :heading="$entry->get('title')"
            :subheading="$entry->get('subheading')"
            :eyebrow="$asOf ? 'As of '.$asOf->format('F Y').'.' : null"
        />

        @if ($sections->isNotEmpty())
            <dl class="divide-y divide-zinc-950/10 border-t border-zinc-950/10 dark:divide-white/10 dark:border-white/10">
                @foreach ($sections as $section)
                    <div class="grid gap-x-10 gap-y-2 py-6 sm:grid-cols-[10rem_minmax(0,1fr)]">
                        <dt class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $section['heading'] }}</dt>
                        <dd class="max-w-[42.5rem]">
                            <x-ui.answer :text="$section['content'] ?? ''" />
                        </dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>
</x-layout>
