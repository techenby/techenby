@php
    use App\Support\GiphyUrl;

    $mediaUrl = GiphyUrl::toMediaUrl($url ?? '');
@endphp

@if ($mediaUrl)
    <figure class="space-y-2.5">
        <img src="{{ $mediaUrl }}" alt="{{ $alt ?? '' }}" loading="lazy" class="block h-auto max-h-96 w-auto max-w-full rounded-xl border border-zinc-200 dark:border-white/10">

        <figcaption class="flex max-w-[32.5rem] flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
            @if (filled($caption ?? null))
                <span class="inline-block origin-left font-hand text-[1.1875rem]/[1.375rem] text-enby-purple-text motion-ok:-rotate-1"><span aria-hidden="true">↑</span> {{ $caption }}</span>
            @endif

            <a href="https://giphy.com/gifs/{{ GiphyUrl::id($url) }}" target="_blank" rel="noopener" class="text-sm text-zinc-500 underline decoration-zinc-300 underline-offset-4 hover:text-zinc-800 dark:text-zinc-400 dark:decoration-white/20 dark:hover:text-white">via GIPHY</a>
        </figcaption>
    </figure>
@endif
