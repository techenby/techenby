<x-layout :title="$title">
    <x-container class="max-w-xl">
        <h1 class="text-4xl lg:text-5xl font-black mb-12">
            <span>{{ $title }}</span>
        </h1>

        <x-card :image="$page->photo" :content="$page->content ?? $page->caption" :tags="$page->tags" />
    </x-container>
</x-layout>
