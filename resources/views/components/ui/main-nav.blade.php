@php
    $items = Statamic\Statamic::tag('nav:main')->fetch();
@endphp

@foreach ($items as $item)
    @unless ($loop->first)
        <flux:separator />
    @endunless

    <flux:sidebar.nav>
        @if (filled($item['url']))
            <flux:sidebar.item :href="$item['url']">{{ $item['title'] }}</flux:sidebar.item>

            @foreach ($item['children'] ?? [] as $child)
                <flux:sidebar.item :href="$child['url']">{{ $child['title'] }}</flux:sidebar.item>
            @endforeach
        @else
            <flux:sidebar.group :heading="(string) $item['title']" class="[&>div:first-child]:font-semibold [&>div:first-child]:text-zinc-800 dark:[&>div:first-child]:text-white">
                @foreach ($item['children'] ?? [] as $child)
                    <flux:sidebar.item :href="$child['url']">{{ $child['title'] }}</flux:sidebar.item>
                @endforeach
            </flux:sidebar.group>
        @endif
    </flux:sidebar.nav>
@endforeach
