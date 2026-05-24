<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use Statamic\Facades\Entry;

new class extends Component
{
    public $zoomScenes = [
        [
            'handle' => 'desktop',
            'label' => 'Desktop',
            'image' => 'assets/uses/one-piece-wallpaper.jpg',
            'alt' => 'Andy\'s Default Wallpaper',
        ],
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
    <x-uses.scene />

    @foreach ($zoomScenes as $scene)
        <x-dynamic-component :component="$scene['handle'] === 'desktop' ? 'uses.scene.desktop' : 'uses.scene.zoom'" :$scene />
    @endforeach
</section>
