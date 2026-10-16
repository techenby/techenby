@props(['items' => []])

@php
    $ids = collect($items)->pluck('id')->all();
    $first = $ids[0] ?? null;
@endphp

<nav aria-labelledby="on-this-page" {{ $attributes }}>
    <div
        class="sticky top-0"
        x-data="{
            active: @js($first),
            init() {
                const targets = @js($ids).map((id) => document.getElementById(id)).filter(Boolean)
                let queued = false
                const update = () => {
                    queued = false
                    this.active = targets.filter((target) => target.getBoundingClientRect().top <= 120).at(-1)?.id ?? @js($first)
                }

                {{-- Scroll events don't bubble, so listen during capture to hear the main panel scroll too. --}}
                window.addEventListener('scroll', () => {
                    if (! queued) {
                        queued = true
                        requestAnimationFrame(update)
                    }
                }, { capture: true, passive: true })

                update()
            },
        }"
    >
        <h2 id="on-this-page" class="px-2.5 pb-2.5 text-sm font-semibold text-zinc-600 dark:text-zinc-300">On this page</h2>

        <ul role="list" class="space-y-0.5">
            @foreach ($items as $item)
                <li>
                    <a
                        href="#{{ $item['id'] }}"
                        x-bind:aria-current="active === @js($item['id']) ? 'location' : false"
                        class="block rounded-md px-2.5 py-1.5 text-[0.9375rem]/5 font-medium text-pretty text-zinc-600 hover:text-zinc-800 aria-[current]:bg-zinc-100 aria-[current]:font-bold aria-[current]:text-zinc-800 dark:text-zinc-300 dark:hover:text-white dark:aria-[current]:bg-white/5 dark:aria-[current]:text-white"
                    >{{ $item['text'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
