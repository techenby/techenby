@props(['entry'])

<div class="relative">
    <div class="absolute -left-24 top-16">
        <div class="text-center uppercase">
            <a href="{{ $entry->url }}" class="flex flex-col">
                <span class="leading-none font-light tracking-widest text-sm text-blue-700 dark:text-blue-500">
                    {{ $entry->date->format('M') }}
                </span>
                <span class="leading-none font-black text-3xl text-java-600 dark:text-java-400">{{ $entry->date->format('j') }}</span>
            </a>
            <a href="{{ url('/posts/type/' . $entry->blueprint) }}" aria-label="All {{ $entry->blueprint }} posts">
                <x-ant.svg src="assets/icons/{{ $entry->blueprint }}" class="h-10 w-10 my-6 mx-auto text-mardi dark:text-yellow scale-100 hover:scale-[1.1] hover:rotate-3" />
            </a>
        </div>
    </div>
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
</div>
