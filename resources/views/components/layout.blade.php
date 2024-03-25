<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | {{ config('app.name') }}</title>

    @antlers
    <meta name="description" content="{{ $caption | strip_tags ?? global:description ?? "TechEnby's Site" }}">
    <meta property="og:description" content="{{ caption | strip_tags ?? global:description ?? "TechEnby's Site" }}">
    <meta property="og:url" content="{{ permalink }}">
    <meta property="og:title" content="{{ title ?? site:name }}">
    <meta property="og:site_name" content="{{ site_name ?? config:app:name }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ site:locale }}">
    <meta property="og:image" content="{{ site:permalink }}{{ photo:url }}">
    <meta property="og:image:alt" content='{{ photo:alt }}'>
    <link rel="sitemap" href="/sitemap.xml" type="application/xml">
    <link rel="alternate" type="application/rss+xml" title="RSS Feed" href="/feed.xml">
    @endantlers

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!-- Fathom - beautiful, simple website analytics -->
    <script src="https://cdn.usefathom.com/script.js" data-site="PNLCEQAM" defer></script>
    <!-- / Fathom -->

    @vite(['resources/css/tailwind.css','resources/js/site.js'])
</head>

<body class="bg-purple-50 dark:bg-purple-950 dark:text-gray-200 text-black">
    <div class="relative z-10">
        @antlers
        {{ partial:nav }}
        @endantlers

        {{ $slot }}

        @antlers
        {{ partial:footer }}
        @endantlers
    </div>
    <div class="bg-pattern h-[700px] absolute top-0 inset-x-0 z-[0]">
        <div class="w-full h-full bg-gradient-to-t from-purple-50 dark:from-purple-950"></div>
    </div>
</body>
</html>
