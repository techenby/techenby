<ui-widget title="{{ $title }}" icon="add-entry" href="{{ $collectionUrl }}">
    <div class="flex flex-wrap gap-2 px-4 py-3">
        @foreach ($blueprints as $blueprint)
            <ui-button href="{{ $blueprint['url'] }}" icon="plus" text="{{ $blueprint['title'] }}"></ui-button>
        @endforeach
    </div>
</ui-widget>
