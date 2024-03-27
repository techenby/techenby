<x-layout :title="$title">
    <x-container class="max-w-xl">
        <h1 class="text-4xl lg:text-5xl font-black flex items-center space-x-4 mb-12">
            <x-ant.svg :src="$icon" :aria-label="$title" role="img" class="size-12" />
            <span>{{ $title }}</span>
        </h1>

        <div class="space-y-16">
            @foreach($page->entries->sortByDesc('updated_at') as $entry)
                <x-card :entry="$entry" />
            @endforeach
        </div>
    </x-container>
</x-layout>
