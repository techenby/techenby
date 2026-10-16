@props(['scene', 'activeScene' => 'desk', 'heading', 'type' => null, 'description' => null])

@php
    $isDesk = $scene->slug === 'desk';
@endphp

<div data-uses-view="{{ $scene->slug }}" @if ($activeScene !== $scene->slug) hidden @endif>
    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_16rem]">
        <figure class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs dark:border-white/10 dark:bg-zinc-900">
            <figcaption class="flex min-h-12 flex-wrap items-center justify-between gap-x-4 gap-y-1 border-b border-zinc-200 px-4 py-2 dark:border-white/10">
                <span class="font-pixel text-lg text-zinc-800 [font-variant-ligatures:none] dark:text-white">{{ $scene->title }}</span>

                @if ($isDesk)
                    <span class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                        <span class="flex items-center gap-1.5"><span aria-hidden="true" class="size-2.5 rounded-full bg-enby-purple ring-2 ring-white dark:ring-zinc-900"></span> Something I use</span>
                        <span class="flex items-center gap-1.5"><span aria-hidden="true" class="size-2.5 rounded-full bg-enby-yellow ring-2 ring-zinc-800 dark:ring-white"></span> Take a closer look</span>
                    </span>
                @else
                    <flux:button size="sm" variant="ghost" icon="arrow-left" inset="right" data-uses-view-button="desk">Back to desk</flux:button>
                @endif
            </figcaption>

            <div class="relative aspect-4/3 overflow-hidden bg-zinc-100 dark:bg-zinc-950">
                <img src="{{ $scene->background->url }}" alt="{{ $scene->background->alt }}" class="size-full object-cover [image-rendering:pixelated]">

                {{ $slot }}
            </div>
        </figure>

        <aside aria-live="polite" data-uses-panel class="space-y-2 rounded-xl border border-zinc-200 bg-zinc-50 p-5 lg:self-start dark:border-white/10 dark:bg-white/5">
            <h2 data-uses-panel-name class="font-display text-xl font-semibold tracking-tight text-balance text-zinc-800 dark:text-white">{{ $heading }}</h2>
            <p data-uses-panel-type class="empty:hidden text-sm font-semibold text-enby-purple-text">{{ $type }}</p>
            <p data-uses-panel-description class="empty:hidden text-[0.9375rem]/6 text-pretty text-zinc-600 dark:text-zinc-300">{{ $description }}</p>

            <ul
                role="list"
                data-uses-panel-links
                hidden
                class="flex flex-wrap gap-x-5 gap-y-1 border-t border-zinc-950/10 pt-3 dark:border-white/10 [&>li:nth-child(3n)_a]:decoration-enby-black dark:[&>li:nth-child(3n)_a]:decoration-white [&>li:nth-child(3n+1)_a]:decoration-enby-yellow [&>li:nth-child(3n+2)_a]:decoration-enby-purple"
            ></ul>

            <template data-uses-link-template>
                <li><a target="_blank" rel="noopener" class="text-[0.9375rem] font-bold text-zinc-800 underline decoration-3 underline-offset-5 dark:text-white"></a></li>
            </template>
        </aside>
    </div>
</div>
