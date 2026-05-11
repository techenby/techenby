@props(['handle' => 'main'])

<ul
    role="list"
    {{ $attributes->class('flex max-w-full items-center gap-1 overflow-x-auto font-press-start text-[0.625rem] tracking-wide uppercase [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden') }}
>
    <s:nav :$handle>
        <li class="shrink-0">
            <a
                href="{{ $url }}"
                @if ($is_current || $is_parent) aria-current="page" @endif
                @class([
                    'inline-flex items-center border-2 px-3 py-2 no-underline transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500',
                    'border-neutral-900 bg-yellow-100 text-neutral-900 shadow-[3px_3px_0_0_#171717] dark:border-white/15 dark:bg-orange-400/10 dark:text-orange-300 dark:shadow-none' => $is_current || $is_parent,
                    'border-transparent text-neutral-500 hover:border-neutral-900 hover:bg-white hover:text-orange-700 dark:text-neutral-400 dark:hover:border-white/15 dark:hover:bg-white/5 dark:hover:text-orange-400' => ! ($is_current || $is_parent),
                ])
            >
                {{ $title }}
            </a>
        </li>
    </s:nav>
</ul>
