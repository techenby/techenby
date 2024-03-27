<x-layout :title="$title">
    <x-container class="max-w-prose">
        <h1 class="text-4xl lg:text-5xl font-black mb-12">
            <span>{{ $title }}</span>
        </h1>

        <div class="block bg-white rounded-lg shadow-lg dark:bg-mardi-950 overflow-hidden">
            @if ($page->photo)
            <img src="{{ url($page->photo->url) }}"
                alt="{{ $page->photo->alt }}"
                class="w-full"
            />
            @endif

            <x-content class="p-8" :content="$page->content ?? $page->caption" />
        </div>
    </x-container>
</x-layout>
