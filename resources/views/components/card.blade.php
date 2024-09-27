@props(['entry'])

<article class="relative">
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
    <div class="overflow-hidden rounded-lg shadow-lg">
        @if ($entry->photo)
        <div class="flex-shrink-0">
            <img src="{{ url($entry->photo->url) }}"
                alt="{{ $entry->photo->alt }}"
                class="w-full" />
        </div>
        @endif
        <div class="bg-white dark:bg-mardi-950 p-6">
            <div class="flex-1">
                <a href="{{ $entry->url }}" class="mt-2 block">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        {{ $entry->title }}
                        @if ($entry->blueprint->handle === 'review')
                        : {{ $entry->rating->label() }}
                        @endif
                    </h2>
                    <p class="mt-3 text-base text-slate-500 dark:text-slate-200">{{ strip_tags($entry->caption) }}</p>
                </a>
            </div>
            <div class="mt-6 text-sm font-medium flex flex-wrap gap-4">
                @foreach ($entry->tags as $tag)
                <a href="{{ $tag->url }}" class="hover:underline flex space-x-0.5">
                    <span class="text-blue-700 dark:text-blue-500">#</span>
                    <span class="text-java-600 dark:text-java-400">{{ $tag->slug }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</article>
