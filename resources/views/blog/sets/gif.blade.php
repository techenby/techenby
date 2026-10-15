@php
    use App\Support\GiphyUrl;

    $mediaUrl = GiphyUrl::toMediaUrl($url ?? '');
@endphp

@if ($mediaUrl)
    <figure>
        <img src="{{ $mediaUrl }}" alt="{{ $alt ?? '' }}" loading="lazy" class="mx-auto block max-w-sm">
        <figcaption class="mt-2 text-center font-geist-mono text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
            <a href="https://giphy.com/gifs/{{ GiphyUrl::id($url) }}" target="_blank" rel="noopener" class="hover:text-neutral-900 dark:hover:text-neutral-100">via GIPHY</a>
        </figcaption>
    </figure>
@endif
