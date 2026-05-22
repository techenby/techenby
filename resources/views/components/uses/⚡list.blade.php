<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Statamic\Facades\Entry;

new class extends Component
{
    #[Computed]
    public function items() {
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
};
?>

<section class="uses-list" aria-labelledby="uses-list-heading">
    <div class="uses-list-heading">
        <div>
            <p>Complete Inventory</p>
            <h2 id="uses-list-heading">All Uses Items</h2>
        </div>
        <span>{{ $this->items->count() }} items</span>
    </div>

    <div class="uses-list-grid">
        @foreach ($this->items as $item)
            @php
                $links = collect($item->value('links') ?? [])
                    ->filter(fn ($link) => filled($link['label'] ?? null) && filled($link['url'] ?? null))
                    ->map(fn ($link) => [
                        'label' => $link['label'],
                        'url' => $link['url'],
                    ])
                    ->values();
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
