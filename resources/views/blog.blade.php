@php
    use App\Support\Lately;

    $posts = app(Lately::class)->items(['blog'], PHP_INT_MAX);
@endphp

<x-layout>
    <div class="max-w-4xl space-y-10">
        <x-ui.page-header :heading="$heading ?? $title" :$subheading />

        @if ($posts->isEmpty())
            <flux:text>Nothing published yet.</flux:text>
        @else
            <ol role="list" class="divide-y divide-zinc-950/10 border-t border-zinc-950/10 dark:divide-white/10 dark:border-white/10">
                @foreach ($posts as $post)
                    <x-ui.entry-row :item="$post" />
                @endforeach
            </ol>
        @endif
    </div>
</x-layout>
