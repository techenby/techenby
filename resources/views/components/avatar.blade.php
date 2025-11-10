@props(['avatar'])

@php
    $blob = Arr::random(['rounded-blob-1', 'rounded-blob-2', 'rounded-blob-3', 'rounded-blob-4'])
@endphp

<img src="{{ $avatar }}" class="w-48 h-48 {{ $blob }}" alt="{{ $avatar->alt }}">
