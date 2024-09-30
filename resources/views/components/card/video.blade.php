@aware(['entry'])

<div class="overflow-hidden rounded-lg shadow-lg">
    @if ($entry->video_type == 'embed')
    <iframe src="{{ $entry->video_url }}" class="w-full aspect-video" frameborder="0" allow="fullscreen"></iframe>
    @else
    <video src="{{ $entry->video->url }}" controls></video>
    @endif
    <div class="bg-white dark:bg-mardi-950 p-6">
        <p class="mt-3 text-base text-slate-500 dark:text-slate-200">{{ strip_tags($entry->caption) }}</p>
        <x-card.tags :tags="$entry->tags" />
    </div>
</div>
