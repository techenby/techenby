@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $faqs = Entry::query()
        ->where('collection', 'faqs')
        ->get()
        ->sortBy(fn ($entry) => $entry->get('title'))
        ->values();

    $socials = collect($andy->comms ?? [])->reject(fn ($method) => Str::startsWith((string) $method->url, 'mailto:'));
    $underlines = ['decoration-enby-yellow', 'decoration-enby-purple', 'decoration-enby-black dark:decoration-white'];
@endphp

<x-layout>
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

        @if ($socials->isNotEmpty())
            <section aria-labelledby="ask-me-something" class="space-y-3 rounded-xl border border-zinc-200 bg-zinc-50 p-6 sm:p-8 dark:border-white/10 dark:bg-white/5">
                <h2 id="ask-me-something" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">Got a question I haven&rsquo;t answered?</h2>
                <flux:text class="text-pretty">Ask me on social media. If it comes up more than once, it&rsquo;ll probably end up here.</flux:text>

                <ul role="list" class="flex flex-wrap gap-x-6 gap-y-2 pt-1">
                    @foreach ($socials as $method)
                        <li>
                            <a href="{{ $method->url }}" target="_blank" rel="noopener" class="text-[0.9375rem] font-bold text-zinc-800 underline decoration-3 underline-offset-5 dark:text-white {{ $underlines[$loop->index % count($underlines)] }}">{{ $method->label }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</x-layout>
