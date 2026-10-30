@props(['photos', 'title', 'name', 'model' => null, 'slide' => 'w-1/3 sm:w-1/4 lg:w-1/5'])

@php
    $photos = collect($photos);
@endphp

<div {{ $attributes }}>
    <flux:carousel advance="page" arrows:position="overlap" aria-label="Photos of {{ $title }}">
        @foreach ($photos as $photo)
            <flux:carousel.slide :class="$slide">
                <flux:modal.trigger :$name>
                    <button
                        type="button"
                        aria-haspopup="dialog"
                        x-on:click="setTimeout(() => document.getElementById('{{ $name }}-{{ $loop->index }}')?.scrollIntoView({ block: 'nearest', inline: 'start', behavior: 'instant' }), 50)"
                        class="relative block w-full rounded-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-purple"
                    >
                        <span class="sr-only">View larger:</span>
                        <img src="{{ $photo['square'] }}" alt="{{ $photo['alt'] }}" loading="lazy" class="aspect-square w-full rounded-lg object-cover ring-1 ring-zinc-950/10 dark:ring-white/10">
                    </button>
                </flux:modal.trigger>
            </flux:carousel.slide>
        @endforeach

        @if ($model)
            <flux:carousel.slide :class="$slide">
                <x-ui.stl-viewer :$model :$title compact />
            </flux:carousel.slide>
        @endif
    </flux:carousel>

    <flux:modal :$name variant="bare" class="m-0 h-dvh max-h-none w-full max-w-none overflow-hidden p-0 text-white backdrop:bg-zinc-950/95!">
        <div
            class="flex h-full flex-col"
            x-data="{
                announcement: '',
                init() {
                    const dialog = this.$el.closest('dialog')
                    const carousel = this.$el.querySelector('ui-carousel')

                    {{-- Flux only forwards a few attributes to the dialog, so name it from here. --}}
                    dialog.setAttribute('aria-labelledby', @js($name.'-title'))

                    {{-- Browsers make the scrolling track its own unnamed tab stop. The arrow keys already drive it. --}}
                    carousel.querySelector('[data-flux-carousel-track]').tabIndex = -1

                    {{-- Scrolling between slides doesn't change the DOM, so Flux's live track stays silent. Announce the selected photo instead. --}}
                    new MutationObserver(() => {
                        if (dialog.open) {
                            this.announcement = carousel.querySelector('[data-selected] [data-photo-label]')?.dataset.photoLabel ?? ''
                        }
                    }).observe(carousel, { attributeFilter: ['data-selected'], subtree: true })
                },
            }"
            x-on:keydown.right.window="$el.closest('dialog').open && $el.querySelector('ui-carousel').next()"
            x-on:keydown.left.window="$el.closest('dialog').open && $el.querySelector('ui-carousel').previous()"
        >
            <p aria-live="polite" class="sr-only" x-text="announcement"></p>

            <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6">
                <h2 id="{{ $name }}-title" class="mt-0 font-display text-lg font-semibold tracking-tight text-white">{{ $title }}</h2>

                <flux:modal.close>
                    <flux:button variant="ghost" icon="x-mark" aria-label="Close photos" class="text-white! hover:bg-white/10!" />
                </flux:modal.close>
            </div>

            <flux:carousel snap="mandatory" aria-label="Photos of {{ $title }}" autofocus tabindex="-1" :arrows="$photos->count() > 1" arrows:class="max-sm:hidden" class="min-h-0 flex-1 outline-none [&>[data-flux-carousel-viewport]]:h-full" track:class="h-full gap-0">
                @foreach ($photos as $photo)
                    <flux:carousel.slide id="{{ $name }}-{{ $loop->index }}" class="w-full">
                        <figure
                            class="flex h-full flex-col items-center justify-center gap-4 px-4 pb-6 sm:px-16"
                            data-photo-label="Photo {{ $loop->iteration }} of {{ $photos->count() }}: {{ $photo['alt'] }}"
                            x-on:click.self="$flux.modal('{{ $name }}').close()"
                        >
                            <img src="{{ $photo['full'] }}" alt="" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" loading="lazy" class="min-h-0 w-auto max-w-full flex-1 rounded-lg object-contain">
                            <figcaption class="max-w-prose text-center text-sm text-pretty text-zinc-300">
                                @if ($photos->count() > 1)
                                    <span aria-hidden="true" class="font-semibold text-white">{{ $loop->iteration }} of {{ $photos->count() }}</span>
                                    <span aria-hidden="true" class="text-zinc-500">·</span>
                                @endif
                                {{ $photo['alt'] }}
                            </figcaption>
                        </figure>
                    </flux:carousel.slide>
                @endforeach
            </flux:carousel>
        </div>
    </flux:modal>
</div>
