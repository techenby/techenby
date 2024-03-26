<x-layout :title="$title">
    <x-container>
        <article class="px-4">
            <h1 class="text-5xl font-black max-w-xl mb-12">{{ $title }}</h1>

            <x-card :image="$page->header_image" :content="$content" />
        </article>
    </x-container>
</x-layout>
