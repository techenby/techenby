<ui-widget title="{{ $title }}" icon="link">
    <div class="flex flex-wrap gap-2 px-4 py-3">
        @foreach ($links as $link)
            <ui-button href="{{ $link['url'] }}" icon="{{ $link['icon'] }}" variant="ghost" text="{{ $link['title'] }}"></ui-button>
        @endforeach
    </div>
</ui-widget>
