@props(['entry'])

@php
    $type = $entry->blueprint->handle === 'bard' ? 'text' : $entry->blueprint
@endphp

<article class="relative">
    <x-card.meta />
    <x-dynamic-component :component="'card.' . $type" />
</article>
