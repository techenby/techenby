@props(['entry', 'badge'])

@php
    use App\Support\Lately;

    $lately = app(Lately::class);
    $photos = $lately->photos($entry);
    $models = $lately->models($entry);
    $note = filled($entry->value('note')) ? (string) $entry->value('note') : null;
@endphp

<x-layout :title="$entry->get('title')" :description="$lately->summary($entry)" :updated-at="$entry->lastModified()">
    <x-ui.article :$entry :$badge :reading-minutes="0">
        @if ($photos)
            <x-ui.photo-carousel :$photos :title="$entry->get('title')" :name="'photos-'.$entry->id()" slide="w-1/2 sm:w-1/3" />
        @endif

        @if ($note)
            <div class="font-hand text-3xl/9 text-enby-purple-text">{{ $note }}</div>
        @endif

        {!! $lately->caption($entry) !!}

        @foreach ($models as $model)
            <x-ui.stl-viewer :$model :title="$entry->get('title')" :several="count($models) > 1" />
        @endforeach
    </x-ui.article>
</x-layout>
