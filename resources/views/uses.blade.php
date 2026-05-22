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
    $sceneOrder = ['desk' => 0, 'gridfinity' => 1, 'buddies' => 2];
    $hiddenListItems = ['desk-friends', 'gridfinity'];
    $allItems = $itemsByScene
        ->flatten(1)
        ->reject(fn ($item) => in_array($item->slug(), $hiddenListItems, true))
        ->sortBy([
            fn ($first, $second) => ($sceneOrder[$first->value('scene')] ?? 99) <=> ($sceneOrder[$second->value('scene')] ?? 99),
            fn ($first, $second) => ((int) ($first->value('sort_order') ?? 0)) <=> ((int) ($second->value('sort_order') ?? 0)),
        ])
        ->values();

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
                            @if ($scene['exists'])
                                <img src="{{ $scene['image'] }}" alt="{{ $scene['alt'] }}" class="uses-scene-image">
                            @else
                                <div class="uses-scene-placeholder" role="img" aria-label="{{ $scene['label'] }} photo placeholder">
                                    <span>DROP PHOTO</span>
                                    <strong>public/assets/uses/{{ $scene['handle'] }}-photo-pixelated.jpg</strong>
                                </div>
                            @endif

                            @foreach ($sceneItems as $item)
                                @php
                                    $position = collect(['left', 'top', 'width', 'height'])
                                        ->map(fn ($field) => $field.': '.$item->value($field).'%;')
                                        ->implode(' ');

                                    $links = $itemLinks($item);
                                @endphp

                                <button
                                    type="button"
                                    class="uses-hotspot"
                                    style="{{ $position }}"
                                    data-uses-item="{{ $item->slug() }}"
                                    data-uses-name="{{ $item->value('title') }}"
                                    data-uses-type="{{ $item->value('item_type') }}"
                                    data-uses-description="{{ $item->value('content') }}"
                                    data-uses-links="{{ base64_encode($links->toJson()) }}"
                                    @if ($item->value('action'))
                                        data-uses-action="{{ $item->value('action') }}"
                                    @endif
                                    aria-label="Inspect {{ $item->value('title') }}"
                                >
                                    <span></span>
                                </button>
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

        <section class="uses-list" aria-labelledby="uses-list-heading">
            <div class="uses-list-heading">
                <div>
                    <p>Complete Inventory</p>
                    <h2 id="uses-list-heading">All Uses Items</h2>
                </div>
                <span>{{ $allItems->count() }} items</span>
            </div>

            <div class="uses-list-grid">
                @foreach ($allItems as $item)
                    @php
                        $links = $itemLinks($item);
                    @endphp

                    <article class="uses-list-item">
                        <div class="uses-list-item-heading">
                            <div>
                                <h3>{{ $item->value('title') }}</h3>
                                <p>{{ $item->value('item_type') }}</p>
                            </div>

                            <span>{{ Str::of($item->value('scene'))->replace('-', ' ')->title() }}</span>
                        </div>

                        <p>{{ $item->value('content') }}</p>

                        @if ($links->isNotEmpty())
                            <ul class="uses-list-links">
                                @foreach ($links as $link)
                                    <li>
                                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener">
                                            {{ $link['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    </section>
</x-layout>
