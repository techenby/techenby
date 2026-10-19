<ui-widget title="{{ $title }}" icon="edit-pen" href="{{ $collectionUrl }}">
    @if ($drafts->isEmpty())
        <p class="px-4 py-3 text-sm text-gray-500">No drafts. Everything's out the door.</p>
    @else
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($drafts as $draft)
                <li>
                    <a href="{{ $draft['url'] }}" class="flex items-center justify-between gap-3 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-900">
                        <span class="truncate" v-pre>{{ $draft['title'] }}</span>
                        <span class="flex shrink-0 items-center gap-2 text-xs text-gray-500">
                            <ui-badge size="sm" text="{{ $draft['type'] }}"></ui-badge>
                            <span>{{ $draft['edited'] }}</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</ui-widget>
