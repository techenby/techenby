<section
    class="grid gap-4 border-t border-zinc-950/10 pt-8 dark:border-white/10"
    aria-labelledby="uses-list-heading"
    x-data="{
        search: '',
        types: [],
        matches(title, type) {
            return title.toLowerCase().includes(this.search.trim().toLowerCase())
                && (this.types.length === 0 || this.types.includes(type))
        },
    }"
>
    <h2 id="uses-list-heading" class="font-display text-2xl font-semibold tracking-tight text-zinc-800 dark:text-white">Everything I use</h2>

    <div class="flex gap-4">
        <flux:input x-model="search" placeholder="looking for something?" aria-label="Search" icon="magnifying-glass" />

        <flux:dropdown>
            <flux:button icon="funnel" icon:variant="outline">Filter</flux:button>

            <flux:menu keep-open>
                <flux:menu.checkbox.group x-model="types">
                    <s:taxonomy:types>
                        <flux:menu.checkbox :value="$slug">{{ $title }}</flux:menu.checkbox>
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
                <s:collection:uses>
                    <flux:table.row :key="$id" x-show="matches({{ Js::from((string) $title) }}, {{ Js::from((string) ($types?->slug ?? '')) }})">
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
