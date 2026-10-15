<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\Entry as EntryFacade;
use Statamic\Facades\Term;

class Lately
{
    /**
     * Labels shown beside each entry, keyed by collection handle.
     *
     * @var array<string, string>
     */
    public const TYPES = [
        'blog' => 'Writing',
        'tips' => 'Tip',
    ];

    public function __construct(public BlogSeries $series) {}

    /**
     * The most recent published entries across the given collections, newest first.
     *
     * @param  list<string>  $collections
     * @return Collection<int, array{type: string, date: ?Carbon, title: string, url: string, summary: ?string, series: ?string, part: ?int}>
     */
    public function items(array $collections, int $limit = 5): Collection
    {
        if ($collections === []) {
            return collect();
        }

        return EntryFacade::query()
            ->whereIn('collection', $collections)
            ->whereStatus('published')
            ->get()
            ->map(fn (Entry $entry) => $this->item($entry))
            ->sortByDesc(fn (array $item) => $item['date']?->timestamp ?? 0)
            ->take($limit)
            ->values();
    }

    /**
     * Everything a list row needs to show one entry.
     *
     * @return array{type: string, date: ?Carbon, title: string, url: string, summary: ?string, series: ?string, part: ?int}
     */
    public function item(Entry $entry): array
    {
        return [
            'type' => self::TYPES[$entry->collectionHandle()] ?? Str::singular($entry->collection()->title()),
            'date' => $this->date($entry),
            'title' => (string) $entry->get('title'),
            'url' => (string) $entry->url(),
            'summary' => $this->summary($entry),
            'series' => $this->seriesTitle($entry),
            'part' => $this->series->part($entry),
        ];
    }

    public function date(Entry $entry): ?Carbon
    {
        if ($entry->hasDate()) {
            return Carbon::instance($entry->date());
        }

        $updatedAt = $entry->value('updated_at');

        return is_numeric($updatedAt) ? Carbon::createFromTimestamp($updatedAt) : null;
    }

    /**
     * The entry's description, or the first sentence of its content when it has none.
     */
    public function summary(Entry $entry): ?string
    {
        $description = $entry->value('description');

        if (is_string($description) && trim($description) !== '') {
            return trim($description);
        }

        $content = $entry->value('content');

        if (! is_string($content) || trim($content) === '') {
            return null;
        }

        $text = Str::of(Str::markdown($content))->stripTags()->squish()->toString();

        if (preg_match('/^(.+?[.!?])(?:\s|$)/u', $text, $matches) === 1) {
            return $matches[1];
        }

        return Str::limit($text, 160);
    }

    public function seriesTitle(Entry $entry): ?string
    {
        $slug = Arr::first(Arr::wrap($entry->value('series')));

        if (! is_string($slug) || $slug === '') {
            return null;
        }

        return Term::find("series::{$slug}")?->title();
    }
}
