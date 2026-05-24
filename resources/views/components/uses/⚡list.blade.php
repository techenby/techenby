<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;

new class extends Component
{
    #[Computed]
    public function items(): Collection
    {
        return Entry::query()
            ->where('collection', 'uses')
            ->get()
            ->groupBy(fn ($entry) => $entry->value('scene'))
            ->map(fn ($entries) => $entries
                ->sortBy(fn ($entry) => (int) ($entry->value('sort_order') ?? 0))
            ->values())
            ->flatten(1)
            ->reject(fn ($item) => in_array($item->slug(), ['desk-friends', 'gridfinity'], true))
            ->sortBy('title')
            ->values();
    }

    #[Computed]
    public function scenesById(): Collection
    {
        return Entry::query()
            ->where('collection', 'uses_scenes')
            ->get()
            ->keyBy(fn ($entry) => $entry->id());
    }

    public function sceneTitle($item): string
    {
        $scene = $item->value('scene');
        $sceneId = is_array($scene) ? collect($scene)->first() : $scene;

        return (string) ($this->scenesById->get($sceneId)?->value('title') ?? Str::of($sceneId ?? '')->replace('-', ' ')->title());
    }

    public function typeTitle(mixed $item): string
    {
        $type = collect($item->value('types') ?? [])->first();

        return (string) (Term::find('types::'.$type)?->title() ?? Str::of($type ?? '')->replace('-', ' ')->title());
    }
};
?>

<section class="uses-list" aria-labelledby="uses-list-heading">
    <div class="uses-list-heading">
        <div>
            <p>Complete Inventory</p>
            <h2 id="uses-list-heading">Everything I Use</h2>
        </div>
    </div>

    <div class="min-w-0 max-w-full">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Item</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Links</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->items as $item)
                    <flux:table.row :key="$item->id">
                        <flux:table.cell>{{ $item->title }}</flux:table.cell>

                        <flux:table.cell>{{ $this->typeTitle($item) }}</flux:table.cell>

                        <flux:table.cell>
                            @foreach ($item->links as $link)
                            <flux:button :href="$link->url" variant="ghost" size="sm" inset="top bottom">{{ $link->label }}</flux:button>
                            @endforeach
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</section>
