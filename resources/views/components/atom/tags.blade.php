@props(['tags'])

<ul role="list" {{ $attributes->class("flex flex-wrap gap-2 font-geist-mono text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:text-neutral-400") }} >
    @foreach ($tags as $tag)
        <li class="text-neutral-600 dark:text-neutral-300 ">
            #{{ $tag }}
        </li>
    @endforeach
</ul>
