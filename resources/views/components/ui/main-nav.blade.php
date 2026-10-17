@php
    $items = Statamic\Statamic::tag('nav:main')->fetch();
    $itemClasses = 'h-10! text-zinc-700! hover:text-zinc-800! data-current:text-zinc-800! dark:text-white/80! dark:hover:text-white! dark:data-current:text-white! [&_[data-content]]:text-base [&[data-current]_[data-content]]:font-bold';
@endphp

@foreach ($items as $item)
    @unless ($loop->first)
        <flux:separator />
    @endunless

    <flux:sidebar.nav>
        @if (filled($item['url']))
            <flux:sidebar.item :href="$item['url']" :accent="false" :class="$itemClasses">{{ $item['title'] }}</flux:sidebar.item>

            @foreach ($item['children'] ?? [] as $child)
                <flux:sidebar.item :href="$child['url']" :accent="false" :class="$itemClasses">{{ $child['title'] }}</flux:sidebar.item>
            @endforeach
        @else
            <flux:sidebar.group :heading="(string) $item['title']" class="[&>div:first-child]:font-semibold [&>div:first-child]:text-zinc-600 dark:[&>div:first-child]:text-zinc-300">
                @foreach ($item['children'] ?? [] as $child)
                    <flux:sidebar.item :href="$child['url']" :accent="false" :class="$itemClasses">{{ $child['title'] }}</flux:sidebar.item>
                @endforeach
            </flux:sidebar.group>
        @endif
    </flux:sidebar.nav>
@endforeach
