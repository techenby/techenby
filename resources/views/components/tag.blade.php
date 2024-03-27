<a href="{{ $tag->url }}"
    class="text-gray-600 dark:text-gray-200 mr-3 hover:text-java-600 dark:hover:text-java-400 flex items-center space-x-2">
    <x-ant.svg :src="$tag->icon" :aria-label="$tag->title" role="img" class="size-6" />
    <span>{{ $tag->title }}</span>
</a>
