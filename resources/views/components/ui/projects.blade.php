@props(['projects' => null])

@php
    use Illuminate\Support\Str;

    // An entries field hands back a query for the picks, in the order they were picked, already limited to published entries.
    $projects = collect($projects?->get() ?? []);
@endphp

@if ($projects->isNotEmpty())
    <section aria-labelledby="projects-heading" {{ $attributes->class('max-w-4xl') }}>
        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-950/10 pb-3 dark:border-white/10">
            <h2 id="projects-heading" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">Things I build</h2>
            <a href="/projects" class="text-sm font-bold text-zinc-800 underline decoration-enby-purple decoration-3 underline-offset-5 dark:text-white">All projects</a>
        </div>

        <ul role="list" class="grid gap-4 pt-5 md:grid-cols-2">
            @foreach ($projects as $project)
                @php
                    $logo = $project->augmentedValue('header_image')->value();
                    $summary = Str::of(Str::markdown((string) $project->value('content')))->stripTags()->squish()->toString();
                @endphp

                <li class="flex items-start gap-4 rounded-xl border border-zinc-200 p-[1.125rem] dark:border-white/10">
                    @if ($logo)
                        <img src="{{ $logo->url() }}" alt="" class="size-12 shrink-0 rounded-[0.625rem] object-cover">
                    @else
                        <div aria-hidden="true" class="grid size-12 shrink-0 place-items-center rounded-[0.625rem] bg-zinc-100 font-display text-xl font-semibold text-zinc-500 dark:bg-white/10 dark:text-zinc-300">{{ Str::substr((string) $project->get('title'), 0, 1) }}</div>
                    @endif

                    <div class="min-w-0 space-y-1">
                        <h3 class="font-display text-lg/6 font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $project->get('title') }}</h3>

                        @if ($summary !== '')
                            <p class="text-[0.9375rem]/[1.4375rem] text-pretty text-zinc-600 dark:text-zinc-300">{{ $summary }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif
