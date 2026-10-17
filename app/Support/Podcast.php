<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Throwable;

class Podcast
{
    public const ITUNES_NAMESPACE = 'http://www.itunes.com/dtds/podcast-1.0.dtd';

    /**
     * How long a good feed is kept, and how long to wait before retrying a broken one, in seconds.
     */
    public const CACHE_SECONDS = 3600;

    public const RETRY_SECONDS = 300;

    public function feedUrl(): ?string
    {
        $url = config('services.transistor.feed_url');

        return is_string($url) && trim($url) !== '' ? trim($url) : null;
    }

    public function enabled(): bool
    {
        return $this->feedUrl() !== null;
    }

    /**
     * Published episodes from the Transistor feed, newest first. A missing or broken feed means no episodes.
     *
     * @return Collection<int, array{guid: string, title: string, link: ?string, audio: ?string, date: Carbon, number: ?int, minutes: ?int, show: string}>
     */
    public function episodes(): Collection
    {
        $url = $this->feedUrl();

        if ($url === null) {
            return collect();
        }

        $key = 'podcast.episodes.' . md5($url);
        $episodes = Cache::get($key);

        if (! is_array($episodes)) {
            $episodes = $this->fetch($url);

            Cache::put($key, $episodes ?? [], $episodes === null ? self::RETRY_SECONDS : self::CACHE_SECONDS);
        }

        return collect($episodes ?? [])
            ->map(fn (array $episode) => [...$episode, 'date' => Carbon::parse($episode['date'])])
            ->reject(fn (array $episode) => $episode['date']->isFuture())
            ->sortByDesc(fn (array $episode) => $episode['date']->timestamp)
            ->values();
    }

    /**
     * Read the RSS and iTunes tags Transistor emits.
     *
     * @return list<array{guid: string, title: string, link: ?string, audio: ?string, date: string, number: ?int, minutes: ?int, show: string}>|null
     */
    public function parse(string $xml): ?array
    {
        $previous = libxml_use_internal_errors(true);

        try {
            $rss = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NOCDATA | LIBXML_NONET);
        } catch (Throwable) {
            $rss = false;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        if ($rss === false || ! isset($rss->channel)) {
            return null;
        }

        $show = trim((string) $rss->channel->title);
        $episodes = [];

        foreach ($rss->channel->item as $item) {
            $title = trim((string) $item->title);
            $date = $this->date((string) $item->pubDate);

            if ($title === '' || $date === null) {
                continue;
            }

            $itunes = $item->children(self::ITUNES_NAMESPACE);
            $link = trim((string) $item->link);
            $audio = trim((string) $item->enclosure['url']);
            $guid = trim((string) $item->guid);
            $number = trim((string) $itunes->episode);

            $episodes[] = [
                'guid' => $guid !== '' ? $guid : ($link !== '' ? $link : $title),
                'title' => $title,
                'link' => $link !== '' ? $link : null,
                'audio' => $audio !== '' ? $audio : null,
                'date' => $date->toIso8601String(),
                'number' => ctype_digit($number) ? (int) $number : null,
                'minutes' => $this->minutes((string) $itunes->duration),
                'show' => $show,
            ];
        }

        return $episodes;
    }

    /**
     * Turn an itunes:duration of seconds, mm:ss or hh:mm:ss into whole minutes.
     */
    public function minutes(string $duration): ?int
    {
        $parts = explode(':', trim($duration));

        if ($parts === [''] || count($parts) > 3 || ! collect($parts)->every(fn (string $part) => ctype_digit($part))) {
            return null;
        }

        $seconds = collect(array_reverse($parts))->reduce(fn (int $total, string $part, int $index) => $total + (int) $part * 60 ** $index, 0);

        return $seconds > 0 ? max(1, (int) round($seconds / 60)) : null;
    }

    /**
     * @return list<array{guid: string, title: string, link: ?string, audio: ?string, date: string, number: ?int, minutes: ?int, show: string}>|null
     */
    private function fetch(string $url): ?array
    {
        try {
            $response = Http::timeout(5)->get($url);
        } catch (ConnectionException) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        return $this->parse($response->body());
    }

    private function date(string $pubDate): ?Carbon
    {
        if (trim($pubDate) === '') {
            return null;
        }

        try {
            return Carbon::parse($pubDate);
        } catch (Throwable) {
            return null;
        }
    }
}
