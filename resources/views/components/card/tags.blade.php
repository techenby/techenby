@props(['tags'])

<div class="mt-6 text-sm font-medium flex flex-wrap gap-4">
    @foreach ($tags as $tag)
    <a href="{{ $tag->url }}" class="hover:underline flex space-x-0.5">
        <span class="text-blue-700 dark:text-blue-500">#</span>
        <span class="text-java-600 dark:text-java-400">{{ $tag->slug }}</span>
    </a>
    @endforeach
</div>
