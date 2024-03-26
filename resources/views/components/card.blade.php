@props(['image' => false, 'content' => false])

<div>
    @if ($image)
    <img src="{{ url($image->url) }}"
        alt="{{ $image->alt }}"
        class="rounded-t-lg w-full shadow-3xl"
    />
    @endif

    <x-content class="{{ $image ? 'rounded-t-none' : '' }}" :content="$content" />
</div>
