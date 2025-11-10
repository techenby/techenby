<x-layout :title="$title">
    <section class="max-w-lg mx-auto py-8 px-4
        lg:py-12 lg:px-0 lg:max-w-none lg:flex lg:items-center lg:justify-center lg:space-x-12">
        <x-avatar :avatar="$brand->avatar" />
        <div class="mt-8 lg:mt-0 max-w-prose">
            <h1 class="text-xl sm:text-3xl font-black align-baseline">
                {!! $brand->heading !!}
                <x-ant.svg src="assets/logo" class="h-8 inline" alt="TechEnby Logo" />
            </h1>
            <div class="mt-4 prose lg:prose-xl dark:prose-invert prose-brand">
                {!! $brand->bio !!}
            </div>
        </div>
    </section>
    <x-container class="max-w-xl">
        <div class="space-y-16">
            @foreach(Statamic::tag('collection:posts') as $entry)
                <x-card :entry="$entry" />
            @endforeach
        </div>
    </x-container>
</x-layout>
