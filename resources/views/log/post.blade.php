@php
    use App\Support\ArticleOutline;
    use App\Support\BlogCodeHighlighter;
    use App\Support\BlogSeries;
    use Statamic\Facades\Entry;
    use Statamic\Fields\Value;
    use Statamic\Modifiers\Modify;

    $entry = Entry::find($id);
    $content = collect($entry->augmentedValue('content')->value() ?? []);
    $outline = new ArticleOutline;
    $highlighter = app(BlogCodeHighlighter::class);

    // Bard hands back text chunks and sets in order; empty paragraphs around a set come through as bare <p></p>.
    $blocks = $content
        ->map(fn ($item) => $item['type'] === 'text'
            ? ['set' => null, 'html' => $outline->anchor($highlighter->highlight((string) $item['text']))]
            : ['set' => (string) $item['type'], 'values' => collect($item->all())->map(fn ($value) => $value instanceof Value ? $value->value() : $value)->all()])
        ->reject(fn (array $block) => $block['set'] === null && trim(strip_tags($block['html'], '<img><hr><table>')) === '');

    $readingMinutes = max(1, (int) Modify::value($content->all())->readTime(225)->fetch());
    $series = app(BlogSeries::class)->for($entry);
@endphp

<x-layout
    :title="$entry->get('title')"
    :description="$entry->get('description')"
    :social-title="$entry->get('title')"
    :social-image="app(App\Support\SocialCard::class)->url($entry)"
    :updated-at="$entry->lastModified()"
>
    <x-ui.article
        :$entry
        :badge="$series ? $series['title'].(isset($series['part']) ? ' · Part '.$series['part'] : '') : null"
        :description="$entry->get('description')"
        :$readingMinutes
        :headings="$outline->headings()"
        :$series
    >
        @foreach ($blocks as $block)
            @if ($block['set'] === null)
                {!! $block['html'] !!}
            @else
                @includeIf('blog.sets.'.$block['set'], $block['values'])
            @endif
        @endforeach
    </x-ui.article>
</x-layout>
