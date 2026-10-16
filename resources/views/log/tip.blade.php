@php
    use App\Support\ArticleOutline;
    use App\Support\BlogCodeHighlighter;
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $entryContent = (string) ($entry->value('content') ?? '');
    $outline = new ArticleOutline;

    $html = $outline->anchor(app(BlogCodeHighlighter::class)->highlight(Str::markdown($entryContent)));
    $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags($html)) / 225));
@endphp

<x-layout
    :title="$entry->get('title')"
    :description="$entry->get('description')"
    :social-title="$entry->get('title')"
    :social-image="app(App\Support\SocialCard::class)->url($entry)"
>
    <x-ui.article
        :$entry
        badge="Tip"
        :$readingMinutes
        :headings="$outline->headings()"
        :comms="$andy->comms ?? []"
    >
        {!! $html !!}
    </x-ui.article>
</x-layout>
