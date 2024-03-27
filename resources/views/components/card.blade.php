@props(['entry'])

<a href="{{ $entry->url }}" class="block bg-white rounded-lg shadow-lg dark:bg-mardi-950 overflow-hidden">
    @if ($entry->photo)
    <img src="{{ url($entry->photo->url) }}"
        alt="{{ $entry->photo->alt }}"
        class="w-full"
    />
    @endif
    <x-content class="px-4 py-6 space-y-6">
        <h2>{{ $entry->title }}</h2>
        <p>{{ strip_tags($entry->caption) }}</p>
    </x-content>
</a>
