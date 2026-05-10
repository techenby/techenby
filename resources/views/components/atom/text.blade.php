@blaze(fold: true)

@props([
    'size' => null,
])

@php
    $classes = Flux::classes()
        ->add("font-['Geist'] text-pretty")
        ->add(match ($size) {
            'xl' => 'text-lg',
            'lg' => 'text-base',
            default => '[:where(&)]:text-sm',
            'sm' => 'text-xs',
        })
        ->add(match ($size) {
            'xl' => 'mt-7',
            'lg' => 'mt-5',
            default => 'mt-3',
            'sm' => 'mt-3',
        })
        ->add('text-neutral-700 dark:text-neutral-400')
        ;
@endphp

<p {{ $attributes->class($classes) }}>{{ $slot }}</p>
