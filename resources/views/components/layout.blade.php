@props(['andy'])

<!doctype html>
<html lang="{{ Statamic\Facades\Site::current()->shortLocale() }}" class="antialiased">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? Statamic\Facades\Site::current()->name() }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Figtree:wght@300..900&family=Geist:wght@100..900&family=Geist+Mono:wght@100..900&family=IBM+Plex+Mono:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=Lora:wght@400..700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        @vite(['resources/js/site.js', 'resources/css/site.css'])
    </head>
    <body class="font-sans">
        <div class="relative isolate min-h-dvh overflow-hidden bg-stone-50 text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 [background-image:repeating-linear-gradient(0deg,transparent_0_3px,rgba(0,0,0,0.04)_3px_4px)] dark:[background-image:repeating-linear-gradient(0deg,transparent_0_3px,rgba(255,255,255,0.035)_3px_4px)]"></div>

            <div class="relative mx-auto grid min-h-dvh max-w-3xl items-start px-6 py-16">
                <div class="min-w-0 w-full">
                    <div class="flex items-center justify-between mr-2 gap-4 font-geist-mono text-xs font-medium tracking-wide text-neutral-500 dark:text-neutral-400">
                        <x-atom.nav class="min-w-0 flex-1" />

                        <x-comms align="bottom" :andy="$andy">
                            <span class="shrink-0 font-geist-mono"><span class="uppercase">Trainer ID</span> @techenby</span>
                        </x-comms>
                    </div>

                    <main class="mt-6 min-w-0 border-2 border-neutral-900 bg-white p-8 shadow-[8px_8px_0_0_#171717] sm:p-10 dark:border-white/10 dark:bg-neutral-900 dark:inset-ring dark:inset-ring-white/5 dark:shadow-none">
                        {{ $slot }}
                    </main>

                    <div class="mt-6 flex items-center justify-between font-geist-mono text-[0.6875rem] uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                        <div class="flex space-x-2">
                            <span>&copy; {{ date('Y') }}</span>
                            <details class="group flex space-x-1 normal-case">
                                <summary class="cursor-pointer list-none hover:text-neutral-900 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-500 [&::-webkit-details-marker]:hidden dark:hover:text-neutral-100">
                                    Andy Swick
                                </summary>
                                <span> (previously Andy Newhouse)</span>
                            </details>
                        </div>
                        @isset($bottomRight)
                        <span>{{ $bottomRight }}</span>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
