@props(['scene'])

<div data-uses-view="desk">
    <div class="uses-scene-grid">
        <div class="uses-scene" aria-label="Interactive desk setup">
            <img src="{{ asset('assets/uses/desk-photo-pixelated.jpg') }}" alt="Pixelated photo of Andy's desk setup" class="uses-scene-image">

            @foreach ($this->itemsByScene->get('desk', collect()) as $item)
                <x-uses.hotspot :$item />
            @endforeach
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
