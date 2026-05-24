@props(['scene'])

<div data-uses-view="{{ $scene['handle'] }}" hidden>
    <div class="uses-scene-grid">
        <div class="uses-zoom">
            <div class="uses-desktop-bar">
                <span>{{ $scene['label'] }}</span>
                <button type="button" data-uses-view-button="desk">Back to desk</button>
            </div>

            <div class="uses-zoom-scene">
                <img src="{{ asset($scene['image']) }}" alt="{{ $scene['alt'] }}" class="uses-scene-image">

                @foreach ($this->itemsByScene->get($scene['handle'], collect()) as $item)
                    <x-uses.hotspot :$item />
                @endforeach
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
