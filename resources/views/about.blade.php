<x-layout :title="$title">
        <article class="px-4">
            <h1 class="text-5xl font-black max-w-xl mb-12 mx-auto">{{ $title }}</h1>

            <div class="flex w-full overflow-x-auto gap-5 sm:gap-8 py-4 -my-4">
                @foreach($photos as $photo)
                    <div class="relative aspect-square w-44 flex-none overflow-hidden rounded-xl snap-center bg-zinc-100 sm:w-72 sm:rounded-2xl dark:bg-zinc-800 {{ rand(0,1) ? 'rotate-2' : '-rotate-2' }}">
                        <img src="{{ $photo->url }}" alt="{{ $photo->alt }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            <x-container>
            <div class="block bg-white rounded-lg shadow-lg dark:bg-mardi-950 overflow-hidden">
                <x-content class="p-8" :content="$page->content ?? $page->caption"/>
            </div>
    </x-container>
        </article>
</x-layout>
