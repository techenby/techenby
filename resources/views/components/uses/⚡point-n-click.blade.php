<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use Statamic\Facades\Entry;

new class extends Component
{
    public $zoomScenes = [
        [
            'handle' => 'gridfinity',
            'label' => 'Gridfinity',
            'image' => 'assets/uses/grid-photo-pixelated.jpg',
            'alt' => 'Pixelated close-up of the Gridfinity section of Andy\'s desk',
        ],
        [
            'handle' => 'buddies',
            'label' => 'Buddies',
            'image' => 'assets/uses/buddies-photo-pixelated.jpg',
            'alt' => 'Pixelated close-up of desk buddies and figures',
        ],
    ];

    public $apps = [
        ['name' => 'PhpStorm', 'meta' => 'Code editor', 'color' => 'bg-orange-400'],
        ['name' => 'Terminal', 'meta' => 'Shell + scripts', 'color' => 'bg-neutral-900 dark:bg-neutral-100'],
        ['name' => 'TablePlus', 'meta' => 'Database checks', 'color' => 'bg-yellow-300'],
        ['name' => 'Raycast', 'meta' => 'Launcher', 'color' => 'bg-red-400'],
        ['name' => 'Arc', 'meta' => 'Browser', 'color' => 'bg-sky-400'],
        ['name' => 'Things', 'meta' => 'Tasks', 'color' => 'bg-emerald-400'],
    ];

    #[Computed]
    public function itemsByScene()
    {
        $scenesById = Entry::query()
            ->where('collection', 'uses_scenes')
            ->get()
            ->keyBy(fn ($entry) => $entry->id());

        return Entry::query()
            ->where('collection', 'uses')
            ->get()
            ->groupBy(function ($entry) use ($scenesById) {
                $scene = $entry->value('scene');
                $sceneId = is_array($scene) ? collect($scene)->first() : $scene;

                return $scenesById->get($sceneId)?->slug() ?? $sceneId;
            })
            ->map(fn ($entries) => $entries
                ->sortBy(fn ($entry) => (int) ($entry->value('sort_order') ?? 0))
                ->values());
    }
};
?>

<section class="section uses-shell" data-uses>
    <div class="uses-toolbar" aria-label="Uses view controls">
        <button type="button" class="uses-tab is-active" data-uses-view-button="desk">Desk</button>
        <button type="button" class="uses-tab" data-uses-view-button="desktop">Desktop</button>
    </div>

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
    @endforeach
</section>
