@php
    $deskImage = asset('assets/uses/desk-photo-pixelated.jpg');
    $hasDeskImage = file_exists(public_path('assets/uses/desk-photo-pixelated.jpg'));

    $items = [
        [
            'id' => 'computer',
            'name' => 'Computer',
            'type' => 'Warp point',
            'description' => 'Jump into the desktop to see the apps, services, and utilities I reach for most often.',
            'action' => 'desktop',
            'position' => 'left: 45%; top: 18%; width: 26%; height: 31%;',
        ],
        [
            'id' => 'keyboard',
            'name' => 'Keyboard',
            'type' => 'Input gear',
            'description' => 'The daily driver for writing code, notes, and tiny commit messages that get longer anyway.',
            'position' => 'left: 31%; top: 61%; width: 31%; height: 13%;',
        ],
        [
            'id' => 'drink',
            'name' => 'Desk Drink',
            'type' => 'Consumable',
            'description' => 'A permanent party member. Usually cold, caffeinated, and within reach.',
            'position' => 'left: 74%; top: 48%; width: 12%; height: 22%;',
        ],
        [
            'id' => 'notebook',
            'name' => 'Notebook',
            'type' => 'Analog save file',
            'description' => 'Where loose plans, half-formed UI ideas, and song snippets go before they become real.',
            'position' => 'left: 10%; top: 56%; width: 17%; height: 24%;',
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

<x-layout :$andy bottomRight="Uses PC · Online">
    <x-fieldset.heading :$eyebrow :$subheading :heading="$heading ?? $title" />

    <section class="section uses-shell" data-uses>
        <div class="uses-toolbar" aria-label="Uses view controls">
            <button type="button" class="uses-tab is-active" data-uses-view-button="desk">Desk</button>
            <button type="button" class="uses-tab" data-uses-view-button="desktop">Desktop</button>
        </div>

        <div data-uses-view="desk">
            <div class="uses-scene-grid">
                <div class="uses-scene" aria-label="Interactive desk setup">
                    @if ($hasDeskImage)
                        <img src="{{ $deskImage }}" alt="Pixelated photo of Andy's desk setup" class="uses-scene-image">
                    @else
                        <div class="uses-scene-placeholder" role="img" aria-label="Desk photo placeholder">
                            <span>DROP PHOTO</span>
                            <strong>public/assets/uses/desk-photo-pixelated.jpg</strong>
                        </div>
                    @endif

                    @foreach ($items as $item)
                        <button
                            type="button"
                            class="uses-hotspot"
                            style="{{ $item['position'] }}"
                            data-uses-item="{{ $item['id'] }}"
                            data-uses-name="{{ $item['name'] }}"
                            data-uses-type="{{ $item['type'] }}"
                            data-uses-description="{{ $item['description'] }}"
                            @isset($item['action'])
                                data-uses-action="{{ $item['action'] }}"
                            @endisset
                            aria-label="Inspect {{ $item['name'] }}"
                        >
                            <span></span>
                        </button>
                    @endforeach
                </div>

                <aside class="uses-panel" aria-live="polite" data-uses-panel>
                    <div class="uses-panel-label">Selected Item</div>
                    <h2 data-uses-panel-name>Move cursor</h2>
                    <p class="uses-panel-type" data-uses-panel-type>Hover, focus, or tap an object.</p>
                    <p data-uses-panel-description>Choose the computer to open the desktop view.</p>
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
    </section>
</x-layout>
