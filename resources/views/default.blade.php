@php
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
@endphp

<x-layout :$title :description="$entry->value('subheading')" :updated-at="$entry->lastModified()">
    <div class="max-w-[42.5rem] space-y-10">
        <x-ui.page-header :heading="$entry->value('heading') ?: $title" :subheading="$entry->value('subheading')" />
    </div>
</x-layout>
