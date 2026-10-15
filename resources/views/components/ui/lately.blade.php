@props(['items' => []])

@php
    $items = collect($items);
@endphp

@if ($items->isNotEmpty())
    <section aria-labelledby="lately-heading" {{ $attributes->class('max-w-4xl') }}>
        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-950/10 pb-3 dark:border-white/10">
            <h2 id="lately-heading" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">Lately</h2>
            <a href="/log" class="text-sm font-bold text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">The whole log</a>
        </div>

        <ol role="list" class="divide-y divide-zinc-950/10 dark:divide-white/10">
            @foreach ($items as $item)
                <x-ui.entry-row :$item />
            @endforeach
        </ol>
    </section>
@endif
