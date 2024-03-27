<x-layout :title="$title">
    <x-container class="max-w-prose">
        <article class="px-4">
            <h1 class="text-5xl font-black max-w-xl mb-12">{{ $title }}</h1>

            <div class="block bg-white rounded-lg shadow-lg dark:bg-mardi-950 overflow-hidden">
                @if ($page->photo)
                <img src="{{ url($page->photo->url) }}"
                    alt="{{ $page->photo->alt }}"
                    class="w-full"
                />
                @endif

                <x-content class="p-8" :content="$page->content ?? $page->caption" />
            </div>
        </article>
    </x-container>
</x-layout>
