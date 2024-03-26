<x-layout title="Tags">
    <x-container class="max-w-xl">
        <h1 class="text-5xl font-black mb-12">Tags</h1>

        <ul class="space-y-4">
            @foreach($tags->orderBy('title')->get() as $tag)
            <li class="text-gray-400 flex items-center justify-between font-medium">
                <a href="{{ $tag->permalink }}" class="group flex w-full justify-between items-center">
                    <div class="text-3xl flex items-center space-x-4 no-underline font-bold text-black dark:text-gray-200 group-hover:text-java-600 dark:group-hover:text-java-400">
                        {!! Statamic::tag('svg')->src($tag->icon)->ariaLabel($tag->title)->role('img')->class('size-12') !!}

                        <span>{{ $tag->title }}</span>
                    </div>

                    <x-blob class="p-4 text-xl text-black dark:text-gray-200">
                        {{ $tag->entries_count }}
                    </x-blob>
                </a>
            </li>
            @endforeach
        </ul>
    </x-container>
</x-layout>
