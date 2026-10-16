@props(['heading', 'subheading' => null])

<header {{ $attributes->class('space-y-4') }}>
    <h1 class="font-display text-4xl font-semibold tracking-tight text-balance text-zinc-800 xl:text-5xl dark:text-white">{{ $heading }}</h1>

    @if (filled($subheading))
        <flux:text class="max-w-[60ch] text-lg text-pretty text-zinc-600 dark:text-zinc-300">{{ $subheading }}</flux:text>
    @endif

    {{ $slot }}
</header>
