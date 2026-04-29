<!doctype html>
<html lang="{{ Statamic\Facades\Site::current()->shortLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? Statamic\Facades\Site::current()->name() }}</title>
        @vite(['resources/js/site.js', 'resources/css/site.css'])
    </head>
    <body class="bg-zinc-100 dark:bg-zinc-900 font-sans leading-normal text-zinc-800 dark:text-zinc-400">
        <div class="mx-auto px-2 lg:min-h-screen flex flex-col items-center justify-center">
            @yield('content')
        </div>
    </body>
</html>
