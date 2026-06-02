@php
    $gitHash = trim(exec('git rev-parse --short HEAD')) ?: 'unknown';
@endphp

<x-layout :$andy :bottomRight="'Save State · '.$gitHash">
    <x-atom.eyebrow>A WILD UPDATE APPEARED!</x-atom.eyebrow>

    <div class="mt-7 grid justify-items-center gap-7 sm:mt-8 sm:grid-cols-[12rem_minmax(0,1fr)] sm:items-start sm:justify-items-start sm:gap-8">
        <button type="button" data-avatar-toggle class="group relative block size-36 cursor-pointer transition duration-300 ease-in-out focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 sm:size-48" aria-label="Swap avatar style" aria-pressed="false">
            <img src="{{ $andy->pixelated_avatar->url() }}" alt="Andy Swick" class="h-full w-full object-cover">

            <img src="{{ $andy->avatar->url() }}" alt="" data-avatar-alternate class="absolute inset-0 invisible h-full w-full object-cover group-hover:visible group-focus-visible:visible">
            <div class="box__line box__line--top"></div>
            <div class="box__line box__line--bottom"></div>
            <div class="box__line box__line--right"></div>
            <div class="box__line box__line--left"></div>
        </button>

        <div class="w-full min-w-0 text-center sm:text-left">
            <x-atom.heading class="mt-0! max-w-none text-[clamp(3.5rem,18vw,4.5rem)] sm:text-7xl">
                Hey, I'm <span class="block sm:inline"><em class="italic text-orange-700 dark:text-orange-400">Andy</em><span class="mt-1 font-geist-mono text-base not-italic sm:ml-1 sm:inline">(they/them)</span></span>
            </x-atom.heading>

            <x-atom.subheading class="mt-5">Current Party:</x-atom.subheading>

            <ul role="list" class="mx-auto mt-3 grid max-w-64 list-disc gap-y-2 pl-5 text-left font-geist-mono text-xs text-neutral-700 sm:mx-0 sm:max-w-none sm:grid-cols-2 sm:gap-x-6 dark:text-neutral-400 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400">
                <li><a href="https://tighten.com/" target="_blank" rel="noopener">Lead Programmer @ Tighten</a></li>
                <li><a href="https://sunnyhome.app" target="_blank" rel="noopener">Sunny</a></li>
                <li><a href="https://www.lego.com/en-us/product/lego-titanic-10294" target="_blank" rel="noopener">LEGO 10294</a></li>
                <li>One Piece Ep.1028</li>
                <li><a href="https://lakesidepride.org/" target="_blank" rel="noopener">LSP</a> <a href="https://givebutter.com/pops-what-was-i-made-for" target="_blank" rel="noopener">Pops Concert</a></li>
                <li>Diet Coke</li>
            </ul>
        </div>
    </div>

    @include('forms.newsletter')

    <div class="mt-9 grid gap-3 border-t-2 border-dashed border-neutral-300 pt-7 dark:border-white/10">
        <x-atom.heading size="default" level="h2" class="!mt-0">
            My life is in <em class="italic text-orange-700 dark:text-orange-400">flux</em>. Come back <em class="italic">soon&minus;ish</em>.
        </x-atom.heading>
        <x-atom.text size="xl" class="!mt-0">
            I&rsquo;m reworking some things. New site germinating in the background. Until then, please enjoy this temporary save screen, or take a look at my <a href="/projects">Projects</a> or <a href="/faq">FAQs</a>.
        </x-atom.text>

        <div class="mt-8 flex items-center justify-end gap-4">
            <x-comms :andy="$andy">
                <span aria-hidden="true" class="text-orange-700 group-open:hidden [animation:blink_1s_step-end_infinite] dark:text-orange-400">&#9654;</span><span aria-hidden="true" class="hidden text-orange-700 group-open:inline dark:text-orange-400">&#9660;</span>
                PRESS START
            </x-comms>
        </div>
    </div>
</x-layout>
