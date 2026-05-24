@props(['scene', 'activeScene' => 'desk'])

<div data-uses-view="{{ $scene->slug }}" @if ($activeScene !== $scene->slug) hidden @endif>
    <div class="uses-scene-grid">
        <div class="uses-zoom">
            <div class="uses-desktop-bar">
                <span>{{ $scene->title }}</span>
                <button type="button" data-uses-view-button="desk">Back to desk</button>
            </div>

            <div class="uses-zoom-scene">
                <img src="{{ $scene->background->url }}" alt="{{ $scene->background->alt }}" class="uses-scene-image">

                <div class="absolute inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
                    <div class="grid max-h-full w-full max-w-184 overflow-y-auto rounded-[1.75rem] border border-white/45 bg-neutral-100/70 shadow-[0_24px_70px_rgba(0,0,0,0.28)] backdrop-blur-xl p-5 dark:border-white/15 dark:bg-neutral-900/65" aria-label="Applications">
                        <div class="flex items-center border-b pb-3 border-neutral-400/45 font-geist font-medium text-neutral-700 dark:border-white/15 dark:text-neutral-100">
                            <span>Software</span>
                        </div>

                        <div class="grid grid-cols-4 gap-x-5 gap-y-4 pt-5 sm:grid-cols-5 lg:grid-cols-4">
                            <s:collection:uses :scene:is="$scene->id" >
                                <button class="grid min-w-0 justify-items-center gap-2 text-center"
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

                                    <img src="{{ $icon->url }}" alt="" class="size-14 rounded-2xl object-contain ">
                                    <span class="max-w-full truncate font-geist text-sm font-medium text-neutral-800 dark:text-neutral-100">{{ $title }}</span>
                                </button>
                            </s:collection:uses>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="uses-panel" aria-live="polite" data-uses-panel>
            <div class="uses-panel-label">Selected Item</div>
            <h2 data-uses-panel-name>{{ $scene['label'] }}</h2>
            <p class="uses-panel-type" data-uses-panel-type>Zoom scene</p>
            <p data-uses-panel-description>Hover, focus, or tap an object.</p>
            <ul class="uses-panel-links" data-uses-panel-links hidden></ul>
        </aside>
    </div>
</div>
