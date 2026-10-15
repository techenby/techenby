@php
    use App\Support\Lately;
    use Statamic\Facades\Entry;

    $lately = app(Lately::class)->items(Entry::find($id)?->value('lately') ?? []);
@endphp

<x-layout>
    <div class="space-y-16">
        <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_20rem] xl:gap-14">
            <div class="space-y-5">
                <h1 class="max-w-[26ch] font-display text-3xl font-semibold tracking-tight text-balance text-zinc-800 xl:text-[2.75rem]/13 dark:text-white">{{ $heading }}</h1>
                <flux:text class="max-w-[60ch] text-lg text-pretty text-zinc-600 dark:text-zinc-300">{{ $subheading }}</flux:text>
            </div>
            <x-ui.current-party :members="$andy->current_party" class="w-full max-w-md self-start" />
        </div>

        <x-ui.lately :items="$lately" />
    </div>
</x-layout>
