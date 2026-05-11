@props(['align' => 'top'])
@aware(['andy'])


<details class="group relative">
    <summary class="cursor-pointer list-none font-press-start text-[0.625rem] text-neutral-500 select-none hover:text-neutral-900 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 [&::-webkit-details-marker]:hidden dark:text-neutral-400 dark:hover:text-neutral-100">
        {{ $slot }}
    </summary>
    <div
        @class([
            'bottom-full mb-3' => $align === 'top',
            'top-full mt-3' => $align === 'bottom',
            'absolute right-0 z-10 w-56 border-2 border-neutral-900 bg-white p-4 shadow-[6px_6px_0_0_#171717] dark:border-white/15 dark:bg-neutral-800 dark:inset-ring dark:inset-ring-white/5 dark:shadow-none'
        ])
    >
        <x-atom.subheading color="orange">Trainer comms</x-atom.subheading>
        <ul role="list" class="mt-3 grid gap-2 font-press-start text-[0.625rem] uppercase tracking-wide text-neutral-900 dark:text-neutral-100">
            @foreach ($andy->comms as $method)
            <li>
                <a href="{{ $method->url }}" target="_blank" rel="noopener" class="group/item flex items-center gap-2 outline-none hover:text-orange-700 focus-visible:text-orange-700 dark:hover:text-orange-400 dark:focus-visible:text-orange-400">
                    <span aria-hidden="true" class="text-orange-700 opacity-0 group-hover/item:opacity-100 group-focus-visible/item:opacity-100 dark:text-orange-400">&#9654;</span>
                    {{ $method->label }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</details>
