@php
    use App\Support\Lately;

    $tips = app(Lately::class)->items(['tips'], PHP_INT_MAX);
@endphp

<x-layout>
    <div class="max-w-4xl space-y-10">
        <x-ui.page-header :heading="$heading ?? $title" :$subheading />

        @if ($tips->isEmpty())
            <flux:text>Nothing published yet.</flux:text>
        @else
            <ol role="list" class="divide-y divide-zinc-950/10 border-t border-zinc-950/10 dark:divide-white/10 dark:border-white/10">
                @foreach ($tips as $tip)
                    <x-ui.entry-row :item="$tip" />
                @endforeach
            </ol>
        @endif
    </div>
</x-layout>
