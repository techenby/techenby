@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $faqs = Entry::query()
        ->where('collection', 'faqs')
        ->orderBy('title')
        ->get();
@endphp

<x-layout :$title :updated-at="$last_modified ?? null" hide-email>
    <div class="max-w-[42.5rem] space-y-12">
        <x-ui.page-header :heading="$heading ?? $title" :$subheading />

        <div class="divide-y divide-zinc-950/10 border-y border-zinc-950/10 dark:divide-white/10 dark:border-white/10">
            @foreach ($faqs as $faq)
                <section id="{{ $faq->slug() }}" aria-labelledby="{{ $faq->slug() }}-question" class="scroll-mt-6 space-y-3 py-8">
                    <h2 id="{{ $faq->slug() }}-question" class="font-display text-2xl font-semibold tracking-tight text-balance text-zinc-800 dark:text-white">{{ $faq->get('title') }}</h2>
                    <div class="article-prose no-lede">
                        {!! Str::markdown($faq->value('content') ?? '') !!}
                    </div>
                </section>
            @endforeach
        </div>

        <section aria-labelledby="ask-me-something" class="relative space-y-3 rounded-xl border border-zinc-200 bg-zinc-50 p-6 sm:p-8 dark:border-white/10 dark:bg-white/5">
            <h2 id="ask-me-something" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">Got a question I haven&rsquo;t answered?</h2>
            <flux:text class="text-pretty">Ask me on any of the socials linked at the bottom of this page. If it comes up more than once, it&rsquo;ll probably end up here.</flux:text>

            <svg aria-hidden="true" viewBox="0 0 64 96" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute -bottom-14 left-24 hidden h-24 w-16 text-enby-purple lg:block">
                <path d="M14 6c9 1 25 6 30 17 4 9-1 18-9 19-7 1-12-5-8-11 5-7 17-3 20 8 3 12 0 30-3 47" />
                <path d="M35 76c3 5 6 9 9 12 3-5 7-9 12-12" />
            </svg>
        </section>
    </div>
</x-layout>
