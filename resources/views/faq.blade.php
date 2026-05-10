@extends('layout')

@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $faqs = Entry::query()
        ->where('collection', 'faqs')
        ->get()
        ->sortBy(fn ($entry) => $entry->get('title'))
        ->values();
@endphp

@section('content')
<div class="relative isolate min-h-dvh overflow-hidden bg-stone-50 text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 [background-image:repeating-linear-gradient(0deg,transparent_0_3px,rgba(0,0,0,0.04)_3px_4px)] dark:[background-image:repeating-linear-gradient(0deg,transparent_0_3px,rgba(255,255,255,0.035)_3px_4px)]"></div>

    <div class="relative mx-auto flex min-h-dvh max-w-3xl items-center px-6 py-16">
        <div class="w-full">
            <div class="flex items-center justify-between gap-4 font-['Geist_Mono'] text-xs tracking-wide text-neutral-500 dark:text-neutral-400">
                <a href="/" aria-label="Homepage" class="hover:text-orange-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 dark:hover:text-orange-400">
                    <span class="uppercase">Trainer ID</span> @techenby
                </a>
                <span class="uppercase text-orange-700 dark:text-orange-400">FAQ Dex &middot; {{ $faqs->count() }} Seen</span>
            </div>

            <div class="mt-6 border-2 border-neutral-900 bg-white p-8 shadow-[8px_8px_0_0_#171717] sm:p-10 dark:border-white/10 dark:bg-neutral-900 dark:inset-ring dark:inset-ring-white/5 dark:shadow-none">
                <p class="font-['Press_Start_2P'] text-[0.625rem] uppercase tracking-wide text-orange-700 dark:text-orange-400">&#9654;&nbsp;ASK TRAINER?</p>

                <div class="mt-7 grid gap-5 sm:grid-cols-[13fr_7fr] sm:items-end">
                    <h1 class="max-w-[11ch] font-['Instrument_Serif'] text-6xl tracking-tight text-balance text-neutral-900 sm:text-7xl dark:text-neutral-100">
                        Field notes &amp; FAQs.
                    </h1>
                    <p class="max-w-[34ch] font-['Geist'] text-base text-pretty text-neutral-700 dark:text-neutral-400">
                        A small inventory of answers while the new site keeps leveling up in the background.
                    </p>
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

                <div class="mt-8 flex items-center justify-between gap-4">
                    <a href="/" class="inline-flex items-center gap-2 border-2 border-neutral-900 bg-orange-700 px-5 py-3 font-['Press_Start_2P'] text-[0.6875rem] text-white shadow-[4px_4px_0_0_#171717] transition hover:-translate-y-0.5 hover:bg-orange-800 hover:shadow-[6px_6px_0_0_#171717] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500 dark:border-orange-700 dark:shadow-none">
                        BACK HOME
                    </a>
                    <span class="font-['Geist_Mono'] text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Route 1 / FAQ</span>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between font-['Geist_Mono'] text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                <span>&copy; {{ date('Y') }} Andy <s>Newhouse</s> Swick</span>
                <span>Continue?</span>
            </div>
        </div>
    </div>
</div>
@endsection
