@extends('layout')

@section('content')
<div class="relative isolate min-h-dvh overflow-hidden bg-stone-50 text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 [background-image:repeating-linear-gradient(0deg,transparent_0_3px,rgba(0,0,0,0.04)_3px_4px)] dark:[background-image:repeating-linear-gradient(0deg,transparent_0_3px,rgba(255,255,255,0.035)_3px_4px)]"></div>

    <div class="relative mx-auto grid min-h-dvh max-w-3xl place-items-center px-6 py-16">
        <div class="w-full">
            <div class="flex items-center justify-between font-['Geist_Mono'] text-xs tracking-wide text-neutral-500 dark:text-neutral-400">
                <span><span class="uppercase">Trainer ID</span> @techenby</span>
                <span class="uppercase text-orange-700 dark:text-orange-400">Save State &middot; v0.1.0</span>
            </div>

            <div class="mt-6 border-2 border-neutral-900 bg-white p-8 shadow-[8px_8px_0_0_#171717] sm:p-10 dark:border-white/10 dark:bg-neutral-900 dark:inset-ring dark:inset-ring-white/5 dark:shadow-none">
                <p class="font-['Press_Start_2P'] text-[0.625rem] uppercase tracking-wide text-orange-700 dark:text-orange-400">&#9654;&nbsp;A WILD UPDATE APPEARED!</p>

                <h1 class="mt-7 max-w-[24ch] font-['Instrument_Serif'] text-6xl leading-[1.02] tracking-tight text-balance text-neutral-900 sm:max-w-[20ch] sm:text-7xl dark:text-neutral-100">
                    My life is in <em class="italic text-orange-700 dark:text-orange-400">flux</em>.<br>
                    Come back <em class="italic">soon&minus;ish</em>.
                </h1>

                <p class="mt-7 max-w-[52ch] font-['Geist'] text-lg text-pretty text-neutral-700 dark:text-neutral-400">
                    I&rsquo;m reworking some things. New site germinating in the background &mdash; until then, please enjoy this temporary save screen.
                </p>

                <form action="#" method="post" class="mt-9 border-t-2 border-dashed border-neutral-300 pt-7 dark:border-white/10">
                    <p class="font-['Press_Start_2P'] text-[0.6875rem] text-neutral-900 dark:text-neutral-100">ENBYMAIL &mdash; SUBSCRIBE?</p>
                    <p class="mt-3 max-w-[48ch] font-['Geist'] text-sm text-pretty text-neutral-700 dark:text-neutral-400">Monthly-ish updates: Notes on Laravel, prints in progress, the occasional missing 1&times;2 plate.</p>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-stretch">
                        <label for="newsletter-email" class="sr-only">Email address</label>
                        <input id="newsletter-email" type="email" name="email" required autocomplete="email" placeholder="trainer@route1.com" class="-outline-offset-1 w-full border-2 border-neutral-900 bg-white px-3 py-3 font-['Geist_Mono'] text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline focus:outline-2 focus:outline-orange-600 max-sm:text-base/6 sm:max-w-xs dark:border-white/15 dark:bg-white/5 dark:text-neutral-100 dark:placeholder:text-neutral-500 dark:focus:outline-orange-400">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 border-2 border-neutral-900 bg-white px-5 py-3 font-['Press_Start_2P'] text-[0.6875rem] text-neutral-900 shadow-[4px_4px_0_0_#171717] transition hover:-translate-y-0.5 hover:bg-yellow-200 hover:shadow-[6px_6px_0_0_#171717] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500 dark:border-white/15 dark:bg-white/5 dark:text-neutral-100 dark:shadow-none dark:hover:bg-white/10">
                            CATCH ME
                        </button>
                    </div>
                </form>

                <div class="mt-9 grid gap-3 border-t-2 border-dashed border-neutral-300 pt-7 sm:grid-cols-2 dark:border-white/10">
                    <p class="font-['Press_Start_2P'] text-[0.6875rem] text-neutral-900 dark:text-neutral-100">CURRENT PARTY:</p>
                    <ul role="list" class="grid list-disc grid-cols-2 gap-x-6 gap-y-2 font-['Geist_Mono'] text-xs text-neutral-700 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 sm:col-start-2 dark:text-neutral-400 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400">
                        <li><a href="https://tighten.com/" target="_blank" rel="noopener">Tighten</a></li>
                        <li><a href="https://sunnyhome.app" target="_blank" rel="noopener">Sunny</a></li>
                        <li>One Piece Ep.687</li>
                        <li><a href="https://www.lego.com/en-us/product/lego-titanic-10294" target="_blank" rel="noopener">LEGO 10294</a></li>
                        <li><a href="https://lakesidepride.org/" target="_blank" rel="noopener">LSP</a> <a href="https://calendar.google.com/calendar/u/0/event?eid=MGNlbmVmY3Z2dW01MzA2aWZ1aDM2MHNrN2pfMjAyNjA1MzFUMDAwMDAwWiBjX3F0YWExNjY3b2c0azYzazVrbWNhY2c2ZjhrQGc" target="_blank" rel="noopener">Pops Concert</a></li>
                        <li>Diet Coke</li>
                    </ul>
                </div>

                <div class="mt-8 flex items-center justify-between gap-4">
                    <a href="mailto:andy@techenby.com" class="inline-flex items-center gap-2 border-2 border-neutral-900 bg-orange-700 px-5 py-3 font-['Press_Start_2P'] text-[0.6875rem] text-white shadow-[4px_4px_0_0_#171717] transition hover:bg-orange-800 hover:-translate-y-0.5 hover:shadow-[6px_6px_0_0_#171717] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500 dark:border-orange-700 dark:shadow-none">
                        MAIL TRAINER
                    </a>
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
                            </ul>
                        </div>
                    </details>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between font-['Geist_Mono'] text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                <span>&copy; {{ date('Y') }} Andy <s>Newhouse</s> Swick</span>
                <span>Route 1</span>
            </div>
        </div>
    </div>
</div>
@endsection
