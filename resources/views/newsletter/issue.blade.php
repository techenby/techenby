@php
    use App\Support\ArticleOutline;
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;
    use Statamic\Modifiers\Modify;

    $entry = Entry::find($id);
    $outline = new ArticleOutline;

    $html = $outline->anchor(Str::markdown((string) ($entry->value('content') ?? '')));
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
        badge="Enby·Mail"
        :$readingMinutes
        :headings="$outline->headings()"
    >
        {!! $html !!}

        <p><a href="/newsletter#past-issues">Read past issues of Enby·Mail</a></p>
    </x-ui.article>
</x-layout>
