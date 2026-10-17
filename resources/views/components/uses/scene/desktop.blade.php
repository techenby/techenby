@props(['scene', 'activeScene' => 'desk'])

<x-uses.scene-frame
    :$scene
    :$activeScene
    :heading="$scene->title"
    type="Software"
    description="Hover or tap an app to see what it is."
>
    <div class="absolute inset-0 flex items-center justify-center p-3 sm:p-6">
        <div aria-label="Applications" class="max-h-full w-full max-w-3xl overflow-y-auto rounded-2xl border border-white/50 bg-white/70 p-4 shadow-lg backdrop-blur-xl sm:p-5 dark:border-white/15 dark:bg-zinc-900/70">
            <p class="border-b border-zinc-950/10 pb-3 text-sm font-semibold text-zinc-700 dark:border-white/10 dark:text-zinc-200">Applications</p>

            <div class="grid grid-cols-4 gap-x-2 gap-y-3 pt-4 sm:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7">
                <s:collection:uses :scene:is="$scene->id">
                    <button
                        type="button"
                        class="grid min-w-0 justify-items-center gap-1.5 rounded-xl p-2 text-center outline-none hover:bg-white/60 focus-visible:bg-white/60 focus-visible:ring-2 focus-visible:ring-enby-purple data-selected:bg-white/70 data-selected:ring-2 data-selected:ring-enby-purple dark:hover:bg-white/10 dark:focus-visible:bg-white/10 dark:data-selected:bg-white/10"
                        data-uses-item="{{ $slug }}"
                        data-uses-name="{{ $title }}"
                        data-uses-type="{{ $types->title }}"
                        data-uses-description="{{ $content }}"
                        data-uses-links="{{ base64_encode(json_encode($links)) }}"
                        @if ($action)
                            data-uses-action="{{ $action }}"
                        @endif
                        aria-label="Inspect {{ $title }}"
                    >
                        <img src="{{ $icon->url }}" alt="" class="size-10 rounded-xl object-contain sm:size-12">
                        <span class="max-w-full truncate text-xs font-medium text-zinc-800 sm:text-sm dark:text-zinc-100">{{ $title }}</span>
                    </button>
                </s:collection:uses>
            </div>
        </div>
    </div>
</x-uses.scene-frame>
