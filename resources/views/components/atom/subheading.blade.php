@props(['color' => null])

@php
    $classes = Flux::classes()
        ->add("uppercase font-press-start text-[0.6875rem] tracking-wide")
        ->add(match ($color) {
            'orange' => 'text-orange-700 dark:text-orange-400',
            default => 'text-neutral-900 dark:text-neutral-100',
        })
        ;
@endphp

<p {{ $attributes->class($classes) }}>{{ $slot }}</p>
