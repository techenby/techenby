<x-layout :title="$title">
    <x-container class="max-w-prose">
        <article class="px-4">
            <h1 class="text-5xl font-black max-w-xl mb-12">{{ $title }}</h1>

            <div class="block bg-white rounded-lg shadow-lg dark:bg-mardi-950 overflow-hidden divide-y">
                @foreach(Statamic::tag('collection:talks') as $entry)
                    <x-talk :entry="$entry" />
                @endforeach
            </div>
        </article>
    </x-container>
</x-layout>
