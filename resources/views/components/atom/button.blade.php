@props(['variant' => 'outline', 'type' => 'button'])

@php
    if ($attributes->has('href')) {
        $type = 'link';
    }

    $classes = Flux::classes()
        ->add("inline-flex relative items-center justify-center gap-2 border-2 transition hover:-translate-y-0.5") // General
        ->add('px-5 py-3') // Spacing
        ->add("uppercase font-press-start text-[0.6875rem]") // Lettering Style
        ->add('focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500') // Focus
        ->add('shadow-[4px_4px_0_0_#171717] hover:shadow-[6px_6px_0_0_#171717] dark:shadow-none') // Shadows
        ->add(match ($variant) { // Background color
            'primary' => 'bg-orange-700 hover:bg-orange-800',
            'outline' => 'bg-white hover:bg-yellow-200 dark:bg-white/5 dark:hover:bg-white/10',
        })
        ->add(match ($variant) { // Text color
            'primary' => 'text-white',
            'outline' => 'text-neutral-900 dark:text-neutral-100',
        })
        ->add(match ($variant) { // Border color
            'primary' => 'border-neutral-900 dark:border-orange-700',
            'outline' => 'border-neutral-900 dark:border-white/15',
        })
        ;
@endphp

<?php if ($type === 'link'): ?>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
<?php else: ?>
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
<?php endif; ?>
