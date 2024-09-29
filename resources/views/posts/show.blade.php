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

            <x-content class="p-8">
                @isset($bard)
                @foreach ($bard as $set)
                    <x-dynamic-component :component="'bard.' . $set->type" :set="$set" />
                @endforeach
                @else
                    {!! $page->content ?? $page->caption !!}
                @endif
            </x-content>
        </div>
    </x-container>
</x-layout>
