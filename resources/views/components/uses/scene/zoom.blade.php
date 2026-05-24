@props(['scene', 'activeScene' => 'desk'])

<div data-uses-view="{{ $scene->slug }}" @if ($activeScene !== $scene->slug) hidden @endif>
    <div class="uses-scene-grid">
        <div class="overflow-hidden border-2 border-neutral-900 bg-stone-200 p-3 shadow-[6px_6px_0_0_#171717] dark:border-white/15 dark:bg-neutral-950 dark:shadow-none">
            <div class="relative z-10 flex items-center justify-between border-2 border-neutral-900 bg-neutral-900 px-3 py-2 font-press-start text-[0.625rem] uppercase text-white dark:border-white/15">
                <span>{{ $scene->title }}</span>
                <button type="button" data-uses-view-button="desk" class="text-orange-300 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500">Back to desk</button>
            </div>

            <div class="relative mt-3 aspect-4/3 overflow-hidden border-2 border-neutral-900 bg-stone-200 dark:border-white/15 dark:bg-neutral-950">
                <img src="{{ $scene->background->url }}" alt="{{ $scene->background->alt }}" class="uses-scene-image">

                <s:collection:uses :scene:is="$scene->id" scope="item">
                    <x-uses.hotspot :$item />
                </s:collection:uses>
            </div>
        </div>

        <aside class="uses-panel" aria-live="polite" data-uses-panel>
            <div class="uses-panel-label">Selected Item</div>
            <h2 data-uses-panel-name>{{ $scene->title }}</h2>
            <p class="uses-panel-type" data-uses-panel-type>Zoom scene</p>
            <p data-uses-panel-description>Hover, focus, or tap an object.</p>
            <ul class="uses-panel-links" data-uses-panel-links hidden></ul>
        </aside>
    </div>
</div>
