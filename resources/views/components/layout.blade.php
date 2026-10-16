@props(['title' => null, 'description' => null, 'socialTitle' => null, 'socialImage' => null, 'updatedAt' => null, 'hideEmail' => false])

@php
    use Carbon\CarbonInterface;
    use Statamic\Facades\GlobalSet;
    use Statamic\Fields\Value;

    $updatedAt = $updatedAt instanceof Value ? $updatedAt->value() : $updatedAt;
    $updatedAt = $updatedAt instanceof CarbonInterface ? $updatedAt : null;

    $comms = collect(GlobalSet::findByHandle('andy')?->inCurrentSite()?->toAugmentedCollection()->get('comms')?->value() ?? [])
        ->when($hideEmail, fn ($comms) => $comms->reject(fn ($method) => str_starts_with((string) $method->url, 'mailto:')));
@endphp

<!doctype html>
<html lang="{{ Statamic\Facades\Site::current()->shortLocale() }}" class="antialiased has-[[data-sticky-heading]]:scroll-pt-16">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ filled($title) ? $title.' · '.config('app.name') : config('app.name') }}</title>

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

        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Next:ital,wght@0,200..800;1,200..800&family=Recursive:wght,MONO@300..1000,0..1&family=Gaegu:wght@400;700&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
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
        <flux:sidebar sticky collapsible="mobile" class="overscroll-x-auto! bg-zinc-50 dark:bg-zinc-900">
            <a href="/" class="max-lg:hidden self-start rounded-lg px-2 pt-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-purple">
                <x-ui.wordmark class="h-8 w-auto" />
            </a>

            <flux:sidebar.header class="justify-start! gap-3 px-2">
                <flux:avatar circle size="lg" src="https://unavatar.io/x/techenby" />
                <div>
                    <flux:heading size="lg" class="font-display font-semibold">Andy Swick</flux:heading>
                    <flux:text>they/them</flux:text>
                </div>
            </flux:sidebar.header>
            <x-ui.main-nav />

            <flux:sidebar.spacer />

            <x-ui.spicy-preferences />
        </flux:sidebar>

        <flux:header class="lg:hidden bg-white dark:bg-zinc-800">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <a href="/" class="ms-2 flex items-center rounded-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-enby-purple">
                <x-ui.wordmark class="h-7 w-auto" />
            </a>
            <flux:spacer />
        </flux:header>

        <flux:main inset class="lg:ms-0 lg:overscroll-x-auto! has-[[data-sticky-heading]]:scroll-pt-16">
            {{ $slot }}

            <x-ui.site-footer :$comms :$updatedAt class="mt-16" />
        </flux:main>

        @livewireScripts
        @fluxScripts
    </body>
</html>
