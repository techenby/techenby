@php
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
@endphp

<x-layout :$title :description="$entry->get('subheading')" :updated-at="$entry->lastModified()">
    <div class="max-w-[42.5rem] space-y-12">
        <x-ui.page-header :heading="$entry->get('heading') ?? $entry->get('title')" :subheading="$entry->get('subheading')" />

        <x-ui.newsletter heading="Sign up" description="" />

        <section aria-labelledby="newsletter-whats-in-it" class="space-y-4">
            <h2 id="newsletter-whats-in-it" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">What lands in your inbox</h2>

            <ul role="list" class="divide-y divide-zinc-950/10 border-y border-zinc-950/10 dark:divide-white/10 dark:border-white/10">
                <li class="py-4">
                    <p class="font-semibold text-zinc-800 dark:text-white">What I&rsquo;ve been writing</p>
                    <flux:text class="text-pretty">New posts and tips from the <a href="/log/writing" class="font-semibold text-zinc-800 underline decoration-enby-purple decoration-2 underline-offset-4 dark:text-white">Log</a>, mostly about Laravel and the apps I&rsquo;m building.</flux:text>
                </li>
                <li class="py-4">
                    <p class="font-semibold text-zinc-800 dark:text-white">What I&rsquo;ve been making</p>
                    <flux:text class="text-pretty">3D prints, LEGO builds, and whatever else made it off my desk.</flux:text>
                </li>
                <li class="py-4">
                    <p class="font-semibold text-zinc-800 dark:text-white">Nothing else</p>
                    <flux:text class="text-pretty">Monthly-ish, and only what you signed up for. Unsubscribe from any email.</flux:text>
                </li>
            </ul>
        </section>

        <flux:text class="text-pretty">
            Rather use a feed reader? <a href="{{ route('log.feed') }}" class="font-semibold text-zinc-800 underline decoration-enby-purple decoration-2 underline-offset-4 dark:text-white">Subscribe to the Log&rsquo;s RSS feed</a> instead.
        </flux:text>
    </div>
</x-layout>
