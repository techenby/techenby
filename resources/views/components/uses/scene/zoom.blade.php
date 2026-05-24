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
