@props(['model', 'title', 'compact' => false])

@if ($compact)
    <div {{ $attributes->class('group relative aspect-square overflow-hidden rounded-lg bg-zinc-50 ring-1 ring-zinc-950/10 dark:bg-white/5 dark:ring-white/10') }} data-stl-viewer data-stl-compact data-stl-url="{{ $model['url'] }}">
        <canvas
            data-stl-canvas
            role="img"
            aria-label="3D model of {{ $title }}"
            class="block size-full cursor-grab active:cursor-grabbing"
        ></canvas>

        <p data-stl-status class="sr-only">Loading 3D model…</p>

        {{-- Lucide's hand, which closes into a grab while the model is being dragged. --}}
        <span aria-hidden="true" class="pointer-events-none absolute bottom-1.5 left-1.5 grid size-7 place-items-center rounded-full bg-white/85 text-zinc-700 ring-1 ring-zinc-950/10 dark:bg-zinc-900/85 dark:text-zinc-200 dark:ring-white/10">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 group-data-dragging:hidden">
                <path d="M18 11V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2" />
                <path d="M14 10V4a2 2 0 0 0-2-2a2 2 0 0 0-2 2v2" />
                <path d="M10 10.5V6a2 2 0 0 0-2-2a2 2 0 0 0-2 2v8" />
                <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15" />
            </svg>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden size-4 group-data-dragging:block">
                <path d="M18 11.5V9a2 2 0 0 0-2-2a2 2 0 0 0-2 2v1.4" />
                <path d="M14 10V8a2 2 0 0 0-2-2a2 2 0 0 0-2 2v2" />
                <path d="M10 9.9V9a2 2 0 0 0-2-2a2 2 0 0 0-2 2v5" />
                <path d="M6 14a2 2 0 0 0-2-2a2 2 0 0 0-2 2" />
                <path d="M18 11a2 2 0 1 1 4 0v3a8 8 0 0 1-8 8h-4a8 8 0 0 1-8-8 2 2 0 1 1 4 0" />
            </svg>
        </span>
    </div>
@else
    <figure {{ $attributes->class('space-y-3') }} data-stl-viewer data-stl-url="{{ $model['url'] }}">
        <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-zinc-50 ring-1 ring-zinc-950/10 dark:bg-white/5 dark:ring-white/10">
            <canvas
                data-stl-canvas
                role="img"
                aria-label="3D model of {{ $title }}"
                class="block size-full cursor-grab touch-none active:cursor-grabbing"
            ></canvas>

            <p data-stl-status class="absolute inset-0 grid place-items-center text-sm/5 text-zinc-500 dark:text-zinc-400">Loading 3D model…</p>
        </div>

        <figcaption class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2 text-sm/5 text-zinc-500 dark:text-zinc-400">
            <span>Drag to spin, scroll or pinch to zoom.</span>

            <span class="flex items-center gap-4">
                <button type="button" data-stl-reset class="font-semibold text-zinc-800 underline decoration-enby-purple decoration-2 underline-offset-4 hover:decoration-3 dark:text-white">Reset view</button>
                <a href="{{ $model['url'] }}" download="{{ $model['filename'] }}">Download STL</a>
            </span>
        </figcaption>
    </figure>
@endif
