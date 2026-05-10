@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $faqs = Entry::query()
        ->where('collection', 'faqs')
        ->get()
        ->sortBy(fn ($entry) => $entry->get('title'))
        ->values();
@endphp

<x-layout topRight="FAQ Dex · {{ $faqs->count() }} Seen">
    <p class="font-['Press_Start_2P'] text-[0.625rem] uppercase tracking-wide text-orange-700 dark:text-orange-400">&#9654;&nbsp;ASK TRAINER?</p>

    <div class="mt-7 grid gap-5 sm:grid-cols-[13fr_7fr] sm:items-end">
        <h1 class="max-w-[11ch] font-['Instrument_Serif'] text-6xl tracking-tight text-balance text-neutral-900 sm:text-7xl dark:text-neutral-100">
            FAQ Dex
        </h1>
    </div>

    <div class="mt-9 border-t-2 border-dashed border-neutral-300 pt-7 dark:border-white/10">
        <div class="grid gap-4">
            @foreach ($faqs as $faq)
                <details class="group border-2 border-neutral-900 bg-stone-50 p-4 shadow-[4px_4px_0_0_#171717] open:bg-yellow-50 dark:border-white/15 dark:bg-white/5 dark:shadow-none dark:open:bg-white/10">
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 font-['Press_Start_2P'] text-[0.6875rem] text-neutral-900 select-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 [&::-webkit-details-marker]:hidden dark:text-neutral-100">
                        <span class="text-pretty">{{ $faq->get('title') }}</span>
                        <span aria-hidden="true" class="text-orange-700 group-open:hidden dark:text-orange-400">&#9654;</span>
                        <span aria-hidden="true" class="hidden text-orange-700 group-open:inline dark:text-orange-400">&#9660;</span>
                    </summary>
                    <div class="mt-4 border-t-2 border-dashed border-neutral-300 pt-4 font-['Geist'] text-base text-pretty text-neutral-700 dark:border-white/10 dark:text-neutral-300 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400 [&_p+p]:mt-4">
                        {!! Str::markdown($faq->value('content') ?? '') !!}
                    </div>
                </details>
            @endforeach
        </div>
    </div>

    @include('forms.faq-question')

    <x-slot:bottom-right>
        <div><a href="/">Route 1</a> / FAQ</div>
    </x-slot:bottom-right>
</x-layout>
