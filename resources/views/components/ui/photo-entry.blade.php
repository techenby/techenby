@props(['entry', 'badge'])

@php
    use App\Support\Lately;

    $lately = app(Lately::class);
    $photos = $lately->photos($entry);
    $note = filled($entry->value('note')) ? (string) $entry->value('note') : null;
@endphp

<x-layout :title="$entry->get('title')" :description="$lately->summary($entry)">
    <x-ui.article :$entry :$badge :reading-minutes="0" :comms="$andy->comms ?? []">
        @foreach ($photos as $photo)
            <img src="{{ $photo['full'] }}" alt="{{ $photo['alt'] }}" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" @if (! $loop->first) loading="lazy" @endif class="h-auto max-h-[80vh] w-auto max-w-full rounded-xl ring-1 ring-zinc-950/10 dark:ring-white/10">
        @endforeach

        @if ($note)
            <div class="font-hand text-3xl/9 text-enby-purple-text">{{ $note }}</div>
        @endif

        {!! $lately->caption($entry) !!}
    </x-ui.article>
</x-layout>
