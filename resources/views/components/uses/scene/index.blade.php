@props(['scene', 'activeScene' => 'desk'])

<div data-uses-view="{{ $scene->slug }}" @if ($activeScene !== $scene->slug) hidden @endif>
    <div class="uses-scene-grid">
        <div class="uses-scene" aria-label="Interactive desk setup">
            <img src="{{ $scene->background->url }}" alt="{{ $scene->background->alt }}" class="uses-scene-image">

            <s:collection:uses :scene:is="$scene->id" scope="item">
                <x-uses.hotspot :$item />
            </s:collection:uses>
        </div>

        <aside class="uses-panel" aria-live="polite" data-uses-panel>
            <div class="uses-panel-label">Selected Item</div>
            <h2 data-uses-panel-name>Move cursor</h2>
            <p class="uses-panel-type" data-uses-panel-type>Hover, focus, or tap an object.</p>
            <p data-uses-panel-description>Choose the computer to open the desktop view.</p>
            <ul class="uses-panel-links" data-uses-panel-links hidden></ul>
        </aside>
    </div>
</div>
