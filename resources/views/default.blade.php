@php
    use Illuminate\Support\Str;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
    $content = trim((string) $entry->value('content'));
@endphp

<x-layout :$title :description="$entry->value('subheading')" :updated-at="$entry->value('updated_at')">
    <div class="max-w-[42.5rem] space-y-10">
        <x-ui.page-header :heading="$entry->value('heading') ?: $title" :subheading="$entry->value('subheading')" :eyebrow="$entry->value('eyebrow')" />

        @if ($content !== '')
            <div class="article-prose no-lede">
                {!! Str::markdown($content) !!}
            </div>
        @endif
    </div>
</x-layout>
