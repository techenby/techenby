@aware(['entry'])

<div class="overflow-hidden rounded-lg shadow-lg">
    @if ($entry->photo)
    <div class="flex-shrink-0">
        <img src="{{ url($entry->photo->url) }}"
            alt="{{ $entry->photo->alt }}"
            class="w-full" />
    </div>
    @endif
    <div class="bg-white dark:bg-mardi-950 p-6">
        <div class="flex-1">
            <a href="{{ $entry->url }}" class="mt-2 block">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    {{ $entry->title }}: {{ $entry->rating->label() }}
                </h2>
                <p class="mt-3 text-base text-slate-500 dark:text-slate-200">{{ strip_tags($entry->caption) }}</p>
            </a>
        </div>
        <x-card.tags :tags="$entry->tags" />
    </div>
</div>
