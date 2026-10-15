<?php

namespace App\Support;

class GiphyUrl
{
    /**
     * Turn any GIPHY page, embed or media link into a direct GIF URL.
     */
    public static function toMediaUrl(string $url): ?string
    {
        $id = self::id($url);

        return $id ? "https://media.giphy.com/media/{$id}/giphy.gif" : null;
    }

    /**
     * Pull the GIF ID out of a GIPHY link, e.g. "rmi45iyhIPuRG" from
     * https://giphy.com/gifs/yes-score-rmi45iyhIPuRG or https://giphy.com/embed/rmi45iyhIPuRG.
     */
    public static function id(string $url): ?string
    {
        $host = parse_url($url, PHP_URL_HOST);
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        if (! is_string($host) || ! str_ends_with($host, 'giphy.com') || $path === '') {
            return null;
        }

        $segments = explode('/', $path);

        $segment = match (true) {
            in_array($segments[0], ['gifs', 'embed', 'media'], true) => $segments[1] ?? null,
            default => $segments[0],
        };

        if ($segment === null) {
            return null;
        }

        $id = str($segment)->beforeLast('.')->afterLast('-')->toString();

        return preg_match('/^[A-Za-z0-9]+$/', $id) ? $id : null;
    }
}
