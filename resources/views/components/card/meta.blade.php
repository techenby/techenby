@aware(['entry'])

@php
    $type = $entry->blueprint->handle === 'bard' ? 'text' : $entry->blueprint
@endphp

<div class="absolute -left-24 top-16">
    <div class="text-center uppercase">
        <a href="{{ $entry->url }}" class="flex flex-col">
            <span class="leading-none font-light tracking-widest text-sm text-blue-700 dark:text-blue-500">
                {{ $entry->date->format('M') }}
            </span>
            <span class="leading-none font-black text-3xl text-java-600 dark:text-java-400">{{ $entry->date->format('j') }}</span>
        </a>
        <a href="{{ url('/posts/type/' . $type) }}" aria-label="All {{ $type }} posts">
            <x-ant.svg src="assets/icons/{{ $type }}" class="h-10 w-10 my-6 mx-auto text-mardi dark:text-yellow scale-100 hover:scale-[1.1] hover:rotate-3" />
        </a>
    </div>
</div>
