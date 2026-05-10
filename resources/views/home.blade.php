<x-layout bottomRight="Save State · v0.1.0">
    <x-atom.eyebrow>A WILD UPDATE APPEARED!</x-atom.eyebrow>

    <x-atom.heading>
        My life is in <em class="italic text-orange-700 dark:text-orange-400">flux</em>.<br>
        Come back <em class="italic">soon&minus;ish</em>.
    </x-atom.heading>

    <x-atom.text size="xl">
        I&rsquo;m reworking some things. New site germinating in the background. Until then, please enjoy this temporary save screen.
    </x-atom.text>

    @include('forms.newsletter')

    <div class="mt-9 grid gap-3 border-t-2 border-dashed border-neutral-300 pt-7 sm:grid-cols-2 dark:border-white/10">
        <x-atom.subheading>Current Party:</x-atom.subheading>
        <ul role="list" class="grid list-disc grid-cols-2 gap-x-6 gap-y-2 font-['Geist_Mono'] text-xs text-neutral-700 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 sm:col-start-2 dark:text-neutral-400 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400">
            <li><a href="https://tighten.com/" target="_blank" rel="noopener">Tighten</a></li>
            <li><a href="https://sunnyhome.app" target="_blank" rel="noopener">Sunny</a></li>
            <li><a href="/faq">FAQ</a></li>
            {{-- <li><a href="https://www.lego.com/en-us/product/lego-titanic-10294" target="_blank" rel="noopener">LEGO 10294</a></li> --}}
            <li>One Piece Ep.743</li>
            <li><a href="https://lakesidepride.org/" target="_blank" rel="noopener">LSP</a> <a href="https://givebutter.com/pops-what-was-i-made-for" target="_blank" rel="noopener">Pops Concert</a></li>
            <li>Diet Coke</li>
        </ul>
    </div>

    <div class="mt-8 flex items-center justify-end gap-4">
        <details class="group relative">
            <summary class="cursor-pointer list-none font-['Press_Start_2P'] text-[0.625rem] text-neutral-500 select-none hover:text-neutral-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 [&::-webkit-details-marker]:hidden dark:text-neutral-400 dark:hover:text-neutral-100">
                <span aria-hidden="true" class="text-orange-700 group-open:hidden [animation:blink_1s_step-end_infinite] dark:text-orange-400">&#9654;</span><span aria-hidden="true" class="hidden text-orange-700 group-open:inline dark:text-orange-400">&#9660;</span>
                PRESS START
            </summary>
            <div class="absolute right-0 bottom-full z-10 mb-3 w-56 border-2 border-neutral-900 bg-white p-4 shadow-[6px_6px_0_0_#171717] dark:border-white/15 dark:bg-neutral-800 dark:inset-ring dark:inset-ring-white/5 dark:shadow-none">
                <p class="font-['Press_Start_2P'] text-[0.5625rem] uppercase tracking-wide text-orange-700 dark:text-orange-400">Trainer comms</p>
                <ul role="list" class="mt-3 grid gap-2 font-['Press_Start_2P'] text-[0.625rem] uppercase tracking-wide text-neutral-900 dark:text-neutral-100">
                    <li>
                        <a href="https://bsky.app/profile/techenby.com" target="_blank" rel="noopener" class="group/item flex items-center gap-2 outline-none hover:text-orange-700 focus-visible:text-orange-700 dark:hover:text-orange-400 dark:focus-visible:text-orange-400">
                            <span aria-hidden="true" class="text-orange-700 opacity-0 group-hover/item:opacity-100 group-focus-visible/item:opacity-100 dark:text-orange-400">&#9654;</span>
                            BLUESKY
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/techenby" target="_blank" rel="noopener" class="group/item flex items-center gap-2 outline-none hover:text-orange-700 focus-visible:text-orange-700 dark:hover:text-orange-400 dark:focus-visible:text-orange-400">
                            <span aria-hidden="true" class="text-orange-700 opacity-0 group-hover/item:opacity-100 group-focus-visible/item:opacity-100 dark:text-orange-400">&#9654;</span>
                            TWITTER
                        </a>
                    </li>
                    <li>
                        <a href="https://youtube.com/@techenby" target="_blank" rel="noopener" class="group/item flex items-center gap-2 outline-none hover:text-orange-700 focus-visible:text-orange-700 dark:hover:text-orange-400 dark:focus-visible:text-orange-400">
                            <span aria-hidden="true" class="text-orange-700 opacity-0 group-hover/item:opacity-100 group-focus-visible/item:opacity-100 dark:text-orange-400">&#9654;</span>
                            YOUTUBE
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/techenby" target="_blank" rel="noopener" class="group/item flex items-center gap-2 outline-none hover:text-orange-700 focus-visible:text-orange-700 dark:hover:text-orange-400 dark:focus-visible:text-orange-400">
                            <span aria-hidden="true" class="text-orange-700 opacity-0 group-hover/item:opacity-100 group-focus-visible/item:opacity-100 dark:text-orange-400">&#9654;</span>
                            GITHUB
                        </a>
                    </li>
                    <li>
                        <a href="mailto:andy@techenby.com" target="_blank" rel="noopener" class="group/item flex items-center gap-2 outline-none hover:text-orange-700 focus-visible:text-orange-700 dark:hover:text-orange-400 dark:focus-visible:text-orange-400">
                            <span aria-hidden="true" class="text-orange-700 opacity-0 group-hover/item:opacity-100 group-focus-visible/item:opacity-100 dark:text-orange-400">&#9654;</span>
                            EMAIL
                        </a>
                    </li>
                </ul>
            </div>
        </details>
    </div>
</x-layout>
