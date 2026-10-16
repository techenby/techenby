@props(['heading', 'subheading' => null, 'eyebrow' => null])

<header {{ $attributes->class('space-y-4') }}>
    @if (filled($eyebrow))
        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ $eyebrow }}</flux:text>
    @endif

    <h1 class="font-display text-4xl font-semibold tracking-tight text-balance text-zinc-800 xl:text-5xl dark:text-white">{{ $heading }}</h1>

    @if (filled($subheading))
        <flux:text class="max-w-[60ch] text-lg text-pretty text-zinc-600 dark:text-zinc-300">{{ $subheading }}</flux:text>
    @endif

    {{ $slot }}
</header>
