@props(['description' => null, 'socialTitle' => null, 'socialImage' => null])

<!doctype html>
<html lang="{{ Statamic\Facades\Site::current()->shortLocale() }}" class="antialiased has-[[data-sticky-heading]]:scroll-pt-16">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? Statamic\Facades\Site::current()->name() }}</title>

        @if (filled($description))
            <meta name="description" content="{{ $description }}">
            <meta property="og:description" content="{{ $description }}">
        @endif

        @if (filled($socialImage))
            <meta property="og:type" content="article">
            <meta property="og:title" content="{{ $socialTitle }}">
            <meta property="og:image" content="{{ $socialImage }}">
            <meta property="og:image:width" content="{{ App\Support\SocialCard::WIDTH }}">
            <meta property="og:image:height" content="{{ App\Support\SocialCard::HEIGHT }}">
            <meta name="twitter:card" content="summary_large_image">
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Next:ital,wght@0,200..800;1,200..800&family=Recursive:wght,MONO@300..1000,0..1&family=Gaegu:wght@400;700&family=Pixelify+Sans:wght@400..700&family=DM+Serif+Display&family=Figtree:wght@300..900&family=Geist:wght@100..900&family=Geist+Mono:wght@100..900&family=IBM+Plex+Mono:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=Lora:wght@400..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        @vite(['resources/js/site.js', 'resources/css/site.css'])

        @fluxAppearance

        <script>
            (() => {
                const root = document.documentElement;
                let textSize = null;
                let reduceMotion = null;

                try {
                    textSize = localStorage.getItem('techenby:text-size');
                    reduceMotion = localStorage.getItem('techenby:reduce-motion');
                } catch {}

                if (['sm', 'base', 'lg'].includes(textSize)) {
                    root.dataset.textSize = textSize;
                }

                root.classList.toggle(
                    'reduce-motion',
                    reduceMotion === null ? matchMedia('(prefers-reduced-motion: reduce)').matches : reduceMotion === 'true',
                );
            })();
        </script>
    </head>
    <body class="enby-stripe min-h-dvh bg-zinc-50 dark:bg-zinc-900">
        <flux:sidebar sticky collapsible="mobile" class="bg-zinc-50 dark:bg-zinc-900">

            <flux:sidebar.header class="justify-evenly">
                <flux:avatar circle size="lg" src="https://unavatar.io/x/techenby" />
                <div>
                    <flux:heading size="lg" class="font-display font-semibold">Andy Swick</flux:heading>
                    <flux:text>they/them ⋅ TechEnby</flux:text>
                </div>
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.item href="/">Home</flux:sidebar.item>
                <flux:sidebar.item href="/log">Log</flux:sidebar.item>
                <flux:sidebar.item href="/projects">Projects</flux:sidebar.item>
                <flux:sidebar.item href="/uses">Uses</flux:sidebar.item>
                <flux:sidebar.item href="/faq">FAQ</flux:sidebar.item>
            </flux:sidebar.nav>
            <flux:separator />
            <flux:sidebar.nav>
                <flux:sidebar.group heading="About me" class="[&>div:first-child]:font-semibold [&>div:first-child]:text-zinc-800 dark:[&>div:first-child]:text-white">
                    <flux:sidebar.item href="/now">Now</flux:sidebar.item>
                    <flux:sidebar.item href="/how-i-work">How I work</flux:sidebar.item>
                    <flux:sidebar.item href="/friends">Friends</flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <x-ui.spicy-preferences />
        </flux:sidebar>

        <flux:header class="lg:hidden bg-white dark:bg-zinc-800">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="start">
                <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                        <flux:menu.radio>Truly Delta</flux:menu.radio>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main inset class="lg:ms-0 has-[[data-sticky-heading]]:scroll-pt-16">
            {{ $slot }}
        </flux:main>

        @livewireScripts
        @fluxScripts
    </body>
</html>
