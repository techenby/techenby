@php
    use Illuminate\Support\Str;

    $underlines = ['decoration-enby-yellow', 'decoration-enby-purple', 'decoration-enby-black dark:decoration-white'];
@endphp

<x-layout>
    <div class="max-w-4xl space-y-10">
        <x-ui.page-header :heading="$heading ?? $title" :$subheading />

        <ul role="list" class="grid gap-4 md:grid-cols-2">
            <s:collection:projects sort="title" scope="project">
                <li class="flex gap-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-white/10 dark:bg-white/5">
                    @if ($project->header_image)
                        <img src="{{ $project->header_image->url }}" alt="" class="size-12 shrink-0 rounded-lg object-contain">
                    @else
                        <div aria-hidden="true" class="grid size-12 shrink-0 place-items-center rounded-lg bg-zinc-100 font-display text-xl font-semibold text-zinc-500 dark:bg-white/10 dark:text-zinc-300">{{ Str::substr($project->title, 0, 1) }}</div>
                    @endif

                    <div class="min-w-0 space-y-2">
                        <h2 class="font-display text-lg/6 font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $project->title }}</h2>

                        @if (filled($project->content))
                            <div class="space-y-3 text-[0.9375rem]/6 text-pretty text-zinc-600 dark:text-zinc-300 [&_a]:font-semibold [&_a]:text-zinc-800 [&_a]:underline [&_a]:decoration-enby-purple [&_a]:decoration-2 [&_a]:underline-offset-4 dark:[&_a]:text-white">
                                {!! Str::markdown((string) $project->content) !!}
                            </div>
                        @endif

                        @if (collect($project->links)->isNotEmpty())
                            <ul role="list" class="flex flex-wrap gap-x-5 gap-y-1 pt-1">
                                @foreach ($project->links as $link)
                                    <li>
                                        <a href="{{ $link->url }}" target="_blank" rel="noopener" class="text-[0.9375rem] font-bold text-zinc-800 underline decoration-3 underline-offset-5 dark:text-white {{ $underlines[$loop->index % count($underlines)] }}">{{ $link->label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </li>
            </s:collection:projects>
        </ul>
    </div>
</x-layout>
