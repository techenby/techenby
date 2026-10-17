@php
    use App\Support\ArticleOutline;
    use App\Support\BlogCodeHighlighter;
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;
    use Statamic\Modifiers\Modify;

    $entry = Entry::find($id);
    $entryContent = (string) ($entry->value('content') ?? '');
    $outline = new ArticleOutline;

    $html = $outline->anchor(app(BlogCodeHighlighter::class)->highlight(Str::markdown($entryContent)));
    $readingMinutes = max(1, (int) Modify::value($html)->readTime(225)->fetch());
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
        badge="Tip"
        :$readingMinutes
        :headings="$outline->headings()"
    >
        {!! $html !!}
    </x-ui.article>
</x-layout>
