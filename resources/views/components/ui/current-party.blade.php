@props(['members' => []])

@php
    $members = collect($members);
@endphp

<section
    aria-labelledby="current-party-heading"
    {{ $attributes->class('overflow-hidden rounded-xl bg-zinc-800 shadow-xl shadow-zinc-900/20 motion-ok:rotate-1 lg:motion-ok:rotate-[1.5deg] dark:shadow-none dark:ring-1 dark:ring-white/8') }}
>
    <div aria-hidden="true">
        <div class="h-1.25 bg-enby-yellow"></div>
        <div class="h-1.25 bg-white"></div>
        <div class="h-1.25 bg-enby-purple"></div>
    </div>

    <div class="px-5 pt-4 pb-5">
        <div class="flex items-baseline justify-between">
            <h2 id="current-party-heading" class="font-pixel text-xl font-semibold text-white">Current Party</h2>
            <p class="font-pixel text-sm text-zinc-400 tabular-nums">{{ $members->count() }}/6</p>
        </div>

        <ol role="list" class="mt-2">
            @foreach ($members as $member)
                <li class="flex gap-3.5 border-b border-white/10 py-2.5 last:border-b-0 last:pb-0">
                    <span aria-hidden="true" class="w-4 shrink-0 font-pixel text-sm/6 text-enby-purple-on-dark">{{ $loop->iteration }}</span>
                    <div class="min-w-0">
                        @if ($member->url)
                            <a href="{{ $member->url }}" target="_blank" rel="noopener" class="font-bold text-white underline decoration-enby-yellow/60 decoration-2 underline-offset-4 hover:decoration-enby-yellow focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-yellow">{{ $member->label }}</a>
                        @else
                            <p class="font-bold text-white">{{ $member->label }}</p>
                        @endif

                        @if ($member->note)
                            <p class="font-hand text-lg/5 text-enby-yellow">{{ $member->note }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
