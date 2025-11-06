<article class="px-3 py-2 space-y-4">
    <p><span>{{ $entry->when }}</span> | <span>{{ $entry->where }}</span></p>

    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $entry->title }}</h2>

    <p>
    @if ($entry->slides)
        <a href="{{ $entry->slides }}" class="text-java-600 dark:text-java-400 hover:underline">Slide deck</a>
    @else
        <span>No Slide deck</span>
    @endif
|
    @if ($entry->video)
        <a href="{{ $entry->video }}" class="text-java-600 dark:text-java-400 hover:underline">Video</a>
    @else
        <span>No Video</span>
    @endif
    </p>
</article>
