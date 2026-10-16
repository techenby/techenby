@php
    use App\Support\ArticleOutline;
    use App\Support\BlogContent;
    use App\Support\BlogSeries;
    use Statamic\Facades\Entry;
    use Statamic\Modifiers\Modify;

    $entry = Entry::find($id);
    $entryContent = $entry->value('content') ?? [];
    $outline = new ArticleOutline;

    $contentBlocks = collect(app(BlogContent::class)->blocks($entryContent))
        ->map(fn (array $block) => $block['type'] === 'html' ? [...$block, 'html' => $outline->anchor($block['html'])] : $block);

    $contentText = Modify::value($entryContent)->bardText()->fetch();
    $readingMinutes = max(1, (int) ceil(str_word_count($contentText) / 225));
    $series = app(BlogSeries::class)->for($entry);
@endphp

<x-layout
    :title="$entry->get('title')"
    :description="$entry->get('description')"
    :social-title="$entry->get('title')"
    :social-image="app(App\Support\SocialCard::class)->url($entry)"
>
    <x-ui.article
        :$entry
        :badge="$series ? $series['title'].(isset($series['part']) ? ' · Part '.$series['part'] : '') : null"
        :description="$entry->get('description')"
        :$readingMinutes
        :headings="$outline->headings()"
        :$series
        :comms="$andy->comms ?? []"
    >
        @foreach ($contentBlocks as $block)
            @if ($block['type'] === 'html')
                {!! $block['html'] !!}
            @else
                @includeIf('blog.sets.'.$block['set'], $block['values'])
            @endif
        @endforeach
    </x-ui.article>
</x-layout>
