<ui-widget title="{{ $title }}" icon="calendar" href="{{ $editUrl }}">
    <template #actions>
        <ui-button href="{{ $editUrl }}" icon="edit" size="sm" text="Update"></ui-button>
    </template>
    <div class="space-y-3 px-4 py-3 text-sm">
        <div class="flex flex-wrap items-center gap-2">
            @if ($asOf)
                <span>As of {{ $asOf->format('F j, Y') }}</span>
            @else
                <span>No "as of" date set</span>
            @endif
            @if ($daysOld !== null)
                <ui-badge size="sm" color="{{ $isStale ? 'amber' : 'green' }}" text="{{ $freshness }}"></ui-badge>
            @endif
        </div>
        @if ($sections->isNotEmpty())
            <div class="flex flex-wrap gap-1.5">
                @foreach ($sections as $section)
                    <ui-badge size="sm" text="{{ $section }}"></ui-badge>
                @endforeach
            </div>
        @endif
    </div>
</ui-widget>
