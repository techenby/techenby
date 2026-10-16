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
    public const COLLECTION = 'log';

    /**
     * @var array<string, string>
     */
    public const TYPES = [
        'post' => 'Writing',
        'tip' => 'Tip',
        'make' => 'Make',
        'moment' => 'Moment',
        'talk' => 'Talk',
    ];

    /**
     * @var list<string>
     */
    public const INLINE_CAPTIONS = ['moment', 'make'];

    /**
     * @var array<string, array{label: string, blueprint: string}>
     */
    public const FILTERS = [
        'writing' => ['label' => 'Writing', 'blueprint' => 'post'],
        'tips' => ['label' => 'Tips', 'blueprint' => 'tip'],
        'makes' => ['label' => 'Makes', 'blueprint' => 'make'],
        'moments' => ['label' => 'Moments', 'blueprint' => 'moment'],
        'talks' => ['label' => 'Talks', 'blueprint' => 'talk'],
    ];

    public function __construct(public BlogSeries $series) {}

    /**
     * @param  list<string>  $blueprints
     * @return Collection<int, array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, caption: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, photos: list<array{square: string, full: string, width: int, height: int, alt: string}>}>
     */
    public function items(array $blueprints, int $limit = 5): Collection
    {
        if ($blueprints === []) {
            return collect();
        }

        return EntryFacade::query()
            ->where('collection', self::COLLECTION)
            ->whereIn('blueprint', $blueprints)
            ->whereStatus('published')
            ->get()
            ->map(fn (Entry $entry) => $this->item($entry))
            ->sortByDesc(fn (array $item) => $item['date']?->timestamp ?? 0)
            ->take($limit)
            ->values();
    }

    /**
     * @return array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, caption: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, photos: list<array{square: string, full: string, width: int, height: int, alt: string}>}
     */
    public function item(Entry $entry): array
    {
        return [
            'id' => (string) $entry->id(),
            'type' => $this->type($entry),
            'date' => $this->date($entry),
            'title' => (string) $entry->get('title'),
            'url' => $entry->url() ?: null,
            'summary' => $this->summary($entry),
            'caption' => in_array($entry->blueprint()->handle(), self::INLINE_CAPTIONS, true) ? $this->caption($entry) : null,
            'series' => $this->seriesTitle($entry),
            'part' => $this->series->part($entry),
            'kind' => $this->kind($entry),
            'note' => filled($entry->value('note')) ? (string) $entry->value('note') : null,
            'photos' => $this->photos($entry),
        ];
    }

    public function type(Entry $entry): string
    {
        return self::TYPES[$entry->blueprint()->handle()] ?? Str::singular($entry->collection()->title());
    }

    /**
     * What sort of thing an entry is within its type: a make's kind, or the event a talk was given at.
     */
    public function kind(Entry $entry): ?string
    {
        $kind = $entry->value('kind');

        if (! is_string($kind) || $kind === '') {
            $event = $entry->value('event');

            return is_string($event) && trim($event) !== '' ? trim($event) : null;
        }

        return $entry->blueprint()->field('kind')?->get('options')[$kind] ?? $kind;
    }

    /**
     * @return list<array{square: string, full: string, width: int, height: int, alt: string}>
     */
    public function photos(Entry $entry): array
    {
        return collect(Arr::wrap($entry->value('photos')))
            ->map(fn ($path) => Asset::find("assets::{$path}"))
            ->filter()
            ->map(fn ($asset) => [
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

    /**
     * The full caption as HTML, for short entries shown whole in the Log.
     */
    public function caption(Entry $entry): ?string
    {
        $content = $entry->value('content');

        if (! is_string($content) || trim($content) === '') {
            return null;
        }

        return trim(Str::markdown($content, ['html_input' => 'strip', 'allow_unsafe_links' => false]));
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
