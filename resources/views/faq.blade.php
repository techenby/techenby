@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $faqs = Entry::query()
        ->where('collection', 'faqs')
        ->get()
        ->sortBy(fn ($entry) => $entry->get('title'))
        ->values();
@endphp

<x-layout bottomRight="FAQ Dex · {{ $faqs->count() }} Seen">
    <x-atom.eyebrow>ASK TRAINER?</x-atom.eyebrow>

    <x-atom.heading>FAQ Dex</x-atom.heading>

    <div class="section">
        <div class="grid gap-4">
            @foreach ($faqs as $faq)
                <details class="group border-2 border-neutral-900 bg-stone-50 p-4 shadow-[4px_4px_0_0_#171717] open:bg-yellow-50 dark:border-white/15 dark:bg-white/5 dark:shadow-none dark:open:bg-white/10">
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 font-press-start text-[0.6875rem] text-neutral-900 select-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 [&::-webkit-details-marker]:hidden dark:text-neutral-100">
                        <span class="text-pretty">{{ $faq->get('title') }}</span>
                        <span aria-hidden="true" class="text-orange-700 group-open:hidden dark:text-orange-400">&#9654;</span>
                        <span aria-hidden="true" class="hidden text-orange-700 group-open:inline dark:text-orange-400">&#9660;</span>
                    </summary>
                    <div class="mt-4 border-t-2 border-dashed border-neutral-300 pt-4 font-geist text-base text-pretty text-neutral-700 dark:border-white/10 dark:text-neutral-300 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400 [&_p+p]:mt-4">
                        {!! Str::markdown($faq->value('content') ?? '') !!}
                    </div>
                </details>
            @endforeach
        </div>
    </div>

    @include('forms.faq-question')
</x-layout>
