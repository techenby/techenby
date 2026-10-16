@props(['comms' => [], 'updatedAt' => null])

@php
    $underlines = ['decoration-enby-yellow', 'decoration-enby-purple', 'decoration-enby-black dark:decoration-white'];
@endphp

<footer {{ $attributes->class('flex flex-wrap items-center justify-between gap-x-6 gap-y-3 border-t border-zinc-200 pt-6 dark:border-white/10') }}>
    <ul role="list" class="flex flex-wrap gap-x-6 gap-y-2">
        @foreach (collect($comms) as $method)
            <li>
                <a href="{{ $method->url }}" class="text-[0.9375rem] font-bold text-zinc-800 underline decoration-3 underline-offset-5 dark:text-white {{ $underlines[$loop->index % count($underlines)] }}">{{ $method->label }}</a>
            </li>
        @endforeach
    </ul>

    @if ($updatedAt)
        <p class="font-hand text-[1.1875rem]/[1.375rem] text-zinc-500 dark:text-zinc-400">
            last tinkered with <time datetime="{{ $updatedAt->toDateString() }}">{{ $updatedAt->format('M j, Y') }}</time>
        </p>
    @endif

    <p class="basis-full text-sm text-zinc-500 dark:text-zinc-400">&copy; {{ now()->year }} Andy Swick <span aria-hidden="true">·</span> TechEnby</p>
</footer>
