{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $title }}</title>
        <link>{{ $link }}</link>
        <atom:link href="{{ $self }}" rel="self" type="application/rss+xml" />
        <description>Everything Andy Swick makes, in one place.</description>
        <language>en</language>
@if ($items->isNotEmpty() && $items->first()['date'])
        <lastBuildDate>{{ $items->first()['date']->toRfc2822String() }}</lastBuildDate>
@endif
@foreach ($items as $item)
        <item>
            <title>{{ $item['title'] }}</title>
            <link>{{ url($item['url'] ?? '/log') }}</link>
            <guid isPermaLink="false">{{ $item['id'] }}</guid>
            <category>{{ $item['type'] }}</category>
@if ($item['date'])
            <pubDate>{{ $item['date']->toRfc2822String() }}</pubDate>
@endif
@if ($item['caption'] ?? $item['summary'])
            <description>{{ $item['caption'] ?? $item['summary'] }}</description>
@endif
        </item>
@endforeach
    </channel>
</rss>
