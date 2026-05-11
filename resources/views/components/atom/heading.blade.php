@props(['size' => 'xl', 'level' => 'h1'])

@php
    $classes = Flux::classes()
        ->add('mt-7 font-instrument-serif tracking-tight text-balance')
        ->add('text-neutral-900 dark:text-neutral-100')
        ->add(match ($size) {
            'xl' => 'text-6xl sm:text-7xl max-w-[24ch] leading-[1.02] sm:max-w-[20ch]',
            default => 'text-3xl sm:text-4xl'
        })
@endphp

<{{ $level }} {{ $attributes->class($classes) }}>
    {{ $slot }}
</{{ $level }}>
