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
        <main id="app" class="isolate">
            @yield('content')
        </main>
    </body>
</html>
