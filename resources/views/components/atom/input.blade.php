@php
    $classes = Flux::classes()
        ->add('w-full')
        ->add('py-3 pr-3 pl-8')
        ->add("font-geist-mono text-sm max-sm:text-base/6")
        ->add('border-2 border-neutral-900 dark:border-white/15')
        ->add('bg-white dark:bg-white/5')
        ->add('text-neutral-900 placeholder:text-neutral-400 dark:text-neutral-100 dark:placeholder:text-neutral-500')
        ->add('focus:outline-2 focus:outline-orange-600 dark:focus:outline-orange-400')
        ;
@endphp

<div class="relative">
    <span aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-press-start text-[0.625rem] text-orange-700 dark:text-orange-400">&#9654;</span>
    <input {{ $attributes->class($classes) }} />
</div>
