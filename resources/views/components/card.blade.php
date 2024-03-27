@props(['image' => false, 'content' => false, 'tags' => [], 'showTitle' => true])

<div>
    @if ($image)
    <img src="{{ url($image->url) }}"
        alt="{{ $image->alt }}"
        class="rounded-t-lg w-full shadow-3xl"
    />
    @endif

    <div class="rounded-lg shadow-3xl bg-white dark:bg-mardi-950 px-4 py-6 space-y-6 {{ $image ? 'rounded-t-none' : '' }}">

        <x-content :content="$content" />

        @if (! empty($tags))
        <div class="flex flex-wrap text-sm space-x-4">
            @foreach($tags as $tag)
            <x-tag :tag="$tag" />
            @endforeach
        </div>
        @endif
    </div>
</div>
