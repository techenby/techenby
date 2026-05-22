<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

@php
    use Statamic\Facades\Entry;
    use Illuminate\Support\Str;

    $deskImage = asset('assets/uses/desk-photo-pixelated.jpg');
    $hasDeskImage = file_exists(public_path('assets/uses/desk-photo-pixelated.jpg'));

    $itemLinks = fn ($item) => collect($item->value('links') ?? [])
        ->filter(fn ($link) => filled($link['label'] ?? null) && filled($link['url'] ?? null))
        ->map(fn ($link) => [
            'label' => $link['label'],
            'url' => $link['url'],
        ])
        ->values();

    $itemsByScene = Entry::query()
        ->where('collection', 'uses')
        ->get()
        ->groupBy(fn ($entry) => $entry->value('scene'))
        ->map(fn ($entries) => $entries
            ->sortBy(fn ($entry) => (int) ($entry->value('sort_order') ?? 0))
            ->values());

    $items = $itemsByScene->get('desk', collect());

    $zoomScenes = [
        [
            'handle' => 'gridfinity',
            'label' => 'Gridfinity',
            'image' => asset('assets/uses/grid-photo-pixelated.jpg'),
            'exists' => file_exists(public_path('assets/uses/grid-photo-pixelated.jpg')),
            'alt' => 'Pixelated close-up of the Gridfinity section of Andy\'s desk',
        ],
        [
            'handle' => 'buddies',
            'label' => 'Buddies',
            'image' => asset('assets/uses/buddies-photo-pixelated.jpg'),
            'exists' => file_exists(public_path('assets/uses/buddies-photo-pixelated.jpg')),
            'alt' => 'Pixelated close-up of desk buddies and figures',
        ],
    ];

    $apps = [
        ['name' => 'PhpStorm', 'meta' => 'Code editor', 'color' => 'bg-orange-400'],
        ['name' => 'Terminal', 'meta' => 'Shell + scripts', 'color' => 'bg-neutral-900 dark:bg-neutral-100'],
        ['name' => 'TablePlus', 'meta' => 'Database checks', 'color' => 'bg-yellow-300'],
        ['name' => 'Raycast', 'meta' => 'Launcher', 'color' => 'bg-red-400'],
        ['name' => 'Arc', 'meta' => 'Browser', 'color' => 'bg-sky-400'],
        ['name' => 'Things', 'meta' => 'Tasks', 'color' => 'bg-emerald-400'],
    ];
@endphp

<section class="section uses-shell" data-uses>
    <div class="uses-toolbar" aria-label="Uses view controls">
        <button type="button" class="uses-tab is-active" data-uses-view-button="desk">Desk</button>
        <button type="button" class="uses-tab" data-uses-view-button="desktop">Desktop</button>
    </div>

    <div data-uses-view="desk">
        <div class="uses-scene-grid">
            <div class="uses-scene" aria-label="Interactive desk setup">
                <img src="{{ asset('assets/uses/desk-photo-pixelated.jpg') }}" alt="Pixelated photo of Andy's desk setup" class="uses-scene-image">

                @foreach ($items as $item)
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

    <div data-uses-view="desktop" hidden>
        <div class="uses-desktop">
            <div class="uses-desktop-bar">
                <span>ANDY OS</span>
                <button type="button" data-uses-view-button="desk">Back to desk</button>
            </div>

            <div class="uses-app-grid">
                @foreach ($apps as $app)
                    <article class="uses-app">
                        <span class="uses-app-icon {{ $app['color'] }}"></span>
                        <h2>{{ $app['name'] }}</h2>
                        <p>{{ $app['meta'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    @foreach ($zoomScenes as $scene)
        @php
            $sceneItems = $itemsByScene->get($scene['handle'], collect());
        @endphp

        <div data-uses-view="{{ $scene['handle'] }}" hidden>
            <div class="uses-scene-grid">
                <div class="uses-zoom">
                    <div class="uses-desktop-bar">
                        <span>{{ $scene['label'] }}</span>
                        <button type="button" data-uses-view-button="desk">Back to desk</button>
                    </div>

                    <div class="uses-zoom-scene">
                        <img src="{{ $scene['image'] }}" alt="{{ $scene['alt'] }}" class="uses-scene-image">

                        @foreach ($sceneItems as $item)
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
    @endforeach
</section>
