@php
    $gitHash = trim(exec('git rev-parse --short HEAD')) ?: 'unknown';
@endphp

<x-layout :$andy :bottomRight="'Save State · '.$gitHash">
    <x-atom.eyebrow>A WILD UPDATE APPEARED!</x-atom.eyebrow>

    <div class="mt-8 flex space-x-8">
        <div class="relative w-48 h-48 group transition duration-300 ease-in-out">
            <img src="{{ $andy->pixelated_avatar->url() }}" alt="Andy Swick" >

            <img src="{{ $andy->avatar->url() }}" alt="" class="absolute top-0 invisible group-hover:visible">
            <div class="box__line box__line--top"></div>
            <div class="box__line box__line--bottom"></div>
            <div class="box__line box__line--right"></div>
            <div class="box__line box__line--left"></div>
        </div>

        <div>
            <x-atom.heading class="!mt-0">
                Hey, I'm <em class="italic text-orange-700 dark:text-orange-400">Andy</em><span class="font-geist-mono text-base">(they/them)</span>
            </x-atom.heading>

            <x-atom.subheading class="mt-5">Current Party:</x-atom.subheading>

            <ul role="list" class="mt-3 ml-6 grid list-disc grid-cols-2 gap-x-6 gap-y-2 font-geist-mono text-xs text-neutral-700 [&_a]:text-orange-700 [&_a]:underline [&_a]:decoration-orange-700/40 [&_a]:underline-offset-4 [&_a:hover]:decoration-orange-700 sm:col-start-2 dark:text-neutral-400 dark:[&_a]:text-orange-400 dark:[&_a]:decoration-orange-400/40 dark:[&_a:hover]:decoration-orange-400">
                <li><a href="https://tighten.com/" target="_blank" rel="noopener">Lead Programmer @ Tighten</a></li>
                <li><a href="https://sunnyhome.app" target="_blank" rel="noopener">Sunny</a></li>
                <li><a href="https://www.lego.com/en-us/product/lego-titanic-10294" target="_blank" rel="noopener">LEGO 10294</a></li>
                <li>One Piece Ep.743</li>
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
