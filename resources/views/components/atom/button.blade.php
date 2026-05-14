@props(['variant' => 'outline', 'type' => 'button', 'size' => null])

@php
    if ($attributes->has('href')) {
        $type = 'link';
    }

    $classes = Flux::classes()
        ->add("inline-flex relative items-center justify-center gap-2 border-2 transition hover:-translate-y-0.5") // General
        ->add('focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500') // Focus
        ->add(match ($size) { // Spacing
            default => 'px-5 py-3',
            'sm' => 'px-3 py-2',
        })
        ->add(match ($size) { // Shadows
            default => 'shadow-[4px_4px_0_0_#171717] hover:shadow-[6px_6px_0_0_#171717] dark:shadow-none',
            'sm' => 'shadow-[3px_3px_0_0_#171717] hover:shadow-[4px_4px_0_0_#171717] dark:shadow-none',
        })
        ->add(match ($variant) { // Lettering Style
            default => "uppercase font-press-start text-[0.6875rem]",
            'ghost' => 'font-geist-mono font-medium uppercase tracking-wide text-[0.6875rem]'
        })
        ->add(match ($variant) { // Background color
            'primary' => 'bg-orange-700 hover:bg-orange-800',
            'outline' => 'bg-white hover:bg-yellow-200 dark:bg-white/5 dark:hover:bg-white/10',
            'ghost' => 'bg-white dark:bg-white/5 dark:shadow-none'
        })
        ->add(match ($variant) { // Text color
            'primary' => 'text-white',
            'outline' => 'text-neutral-900 dark:text-neutral-100',
            'ghost' => 'text-neutral-900 hover:text-orange-700 dark:text-neutral-100 dark:hover:text-orange-400',
        })
        ->add(match ($variant) { // Border color
            'primary' => 'border-neutral-900 dark:border-orange-700',
            'outline' => 'border-neutral-900 dark:border-white/15',
            'ghost' => 'border-neutral-900 dark:border-white/15'
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
