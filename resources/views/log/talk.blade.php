@php
    use App\Support\Lately;
    use App\Support\SocialCard;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $lately = app(Lately::class);
    $photos = $lately->photos($entry);
    $video = $entry->augmentedValue('video')->value();
    $embedUrl = $video?->isEmbeddable() ? $video->embedUrl() : null;
    $slides = filled($entry->value('slides')) ? (string) $entry->value('slides') : null;
    $description = $entry->value('description') ?: $lately->summary($entry);
@endphp

<x-layout
    :title="$entry->get('title')"
    :$description
    :social-title="$entry->get('title')"
    :social-image="app(SocialCard::class)->url($entry)"
    :updated-at="$entry->lastModified()"
>
    <x-ui.article :$entry :badge="$lately->kind($entry) ?? 'Talk'" :description="$entry->value('description')" :reading-minutes="0">
        @if ($embedUrl)
            <iframe
                src="{{ $embedUrl }}"
                title="Video of {{ $entry->get('title') }}"
                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
                loading="lazy"
                class="aspect-video w-full rounded-xl ring-1 ring-zinc-950/10 dark:ring-white/10"
            ></iframe>
        @endif

        @if ($video || $slides)
            <ul role="list" class="flex! flex-wrap gap-x-6 gap-y-2 pl-0! [&>li]:list-none">
                @if ($video)
                    <li><a href="{{ $video->url() }}" target="_blank" rel="noopener" class="font-bold text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">Watch the video</a></li>
                @endif
                @if ($slides)
                    <li><a href="{{ $slides }}" target="_blank" rel="noopener" class="font-bold text-zinc-800 underline decoration-enby-yellow decoration-3 underline-offset-5 dark:text-white">Slides &amp; code</a></li>
                @endif
            </ul>
        @endif

        @foreach ($photos as $photo)
            <img src="{{ $photo['full'] }}" alt="{{ $photo['alt'] }}" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" loading="lazy" class="h-auto max-h-[80vh] w-auto max-w-full rounded-xl ring-1 ring-zinc-950/10 dark:ring-white/10">
        @endforeach

        {!! $lately->caption($entry) !!}
    </x-ui.article>
</x-layout>
