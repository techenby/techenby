<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Contracts\Assets\Asset as AssetContract;
use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\Asset;
use Statamic\Facades\Entry as EntryFacade;
use Statamic\Facades\Term;
use Statamic\Statamic;

class Lately
{
    /**
     * @var array<string, string>
     */
    public const TYPES = [
        'blog' => 'Writing',
        'tips' => 'Tip',
        'makes' => 'Make',
        'moments' => 'Moment',
    ];

    public function __construct(public BlogSeries $series) {}

    /**
     * @param  list<string>  $collections
     * @return Collection<int, array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, photos: list<array{large: string, square: string, full: string, width: int, height: int, alt: string}>}>
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
     * @return array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, photos: list<array{large: string, square: string, full: string, width: int, height: int, alt: string}>}
     */
    public function item(Entry $entry): array
    {
        return [
            'id' => (string) $entry->id(),
            'type' => self::TYPES[$entry->collectionHandle()] ?? Str::singular($entry->collection()->title()),
            'date' => $this->date($entry),
            'title' => (string) $entry->get('title'),
            'url' => $entry->url() ?: null,
            'summary' => $this->summary($entry),
            'series' => $this->seriesTitle($entry),
            'part' => $this->series->part($entry),
            'kind' => $this->kind($entry),
            'note' => filled($entry->value('note')) ? (string) $entry->value('note') : null,
            'photos' => $this->photos($entry),
        ];
    }

    public function kind(Entry $entry): ?string
    {
        $kind = $entry->value('kind');

        if (! is_string($kind) || $kind === '') {
            return null;
        }

        return $entry->blueprint()->field('kind')?->get('options')[$kind] ?? $kind;
    }

    /**
     * @return list<array{large: string, square: string, full: string, width: int, height: int, alt: string}>
     */
    public function photos(Entry $entry): array
    {
        return collect(Arr::wrap($entry->value('photos')))
            ->map(fn ($path) => Asset::find("assets::{$path}"))
            ->filter()
            ->map(fn ($asset) => [
                'large' => $this->glide($asset, ['width' => 1200]),
                'square' => $this->glide($asset, ['width' => 400, 'height' => 400, 'fit' => 'crop_focal']),
                'full' => $this->glide($asset, ['width' => 2000]),
                'width' => (int) $asset->width(),
                'height' => (int) $asset->height(),
                'alt' => (string) $asset->get('alt'),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function glide(AssetContract $asset, array $params): string
    {
        return (string) Statamic::tag('glide')->params(['src' => $asset, ...$params])->fetch();
    }

    public function date(Entry $entry): ?Carbon
    {
        if ($entry->hasDate()) {
            return Carbon::instance($entry->date());
        }

        $updatedAt = $entry->value('updated_at');

        return is_numeric($updatedAt) ? Carbon::createFromTimestamp($updatedAt) : null;
    }

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
