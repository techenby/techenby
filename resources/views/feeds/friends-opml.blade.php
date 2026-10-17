{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<opml version="2.0">
    <head>
        <title>{{ $title }}</title>
        <dateCreated>{{ $updatedAt->toRfc2822String() }}</dateCreated>
    </head>
    <body>
@foreach ($feeds as $feed)
        <outline type="rss" text="{{ $feed['name'] }}" title="{{ $feed['name'] }}" xmlUrl="{{ $feed['rss'] }}"@if (filled($feed['url'] ?? null)) htmlUrl="{{ $feed['url'] }}"@endif />
@endforeach
    </body>
</opml>
