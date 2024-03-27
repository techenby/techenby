@php
    $entries = Statamic\Facades\Entry::query()
        ->where("collection", "posts")
        ->where("blueprint", $type)
        ->orderByDesc('date')
        ->get();
@endphp

<x-layout :title="ucfirst($type)">
    <x-container class="max-w-xl">
        <h1 class="text-4xl lg:text-5xl font-black flex items-center space-x-4 mb-12">
            <x-ant.svg :src="'assets/icons/' . $type" :aria-label="$type" role="img" class="size-12" />
            <span>{{ ucfirst($type) }}</span>
        </h1>

        <div class="space-y-16">
            @foreach($entries as $entry)
                <x-card :entry="$entry" />
            @endforeach
        </div>
    </x-container>
</x-layout>
