<?php

use Livewire\Component;

new class extends Component
{
    public array $filters = [
        'search' => '',
        'types' => [],
    ];
};
?>

<section class="uses-list" aria-labelledby="uses-list-heading">
    <div class="uses-list-heading">
        <div>
            <p>Complete Inventory</p>
            <h2 id="uses-list-heading">Everything I Use</h2>
        </div>
    </div>

    <div class="flex gap-4">
        <flux:input wire:model.live="filters.search" placeholder="looking for something?" aria-label="Search" icon="magnifying-glass" />

        <flux:dropdown>
            <flux:button icon="funnel" icon:variant="outline">Filter</flux:button>

            <flux:menu keep-open>
                <flux:menu.checkbox.group wire:model.live="filters.types">
                    <s:taxonomy:types>
                        <flux:menu.checkbox :value="$id">{{ $title }}</flux:menu.checkbox>
                    </s:taxonomy:types>
                </flux:menu.checkbox.group>
            </flux:menu>
        </flux:dropdown>
    </div>

    <div class="min-w-0 max-w-full">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Item</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Links</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                <s:collection:uses
                    title:contains="{{ $filters['search'] }}"
                    taxonomy:types="{{ implode('|', $filters['types']) }}"
                >
                    <flux:table.row :key="$id">
                        <flux:table.cell>{{ $title }}</flux:table.cell>

                        <flux:table.cell>{{ $types->title }}</flux:table.cell>

                        <flux:table.cell>
                            @foreach ($links as $link)
                            <flux:button :href="$link->url" variant="ghost" size="sm" inset="top bottom">{{ $link->label }}</flux:button>
                            @endforeach
                        </flux:table.cell>
                    </flux:table.row>
                </s:collection:uses>
            </flux:table.rows>
        </flux:table>
    </div>
</section>
