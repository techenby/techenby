<?php

namespace App\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Contracts\Assets\Asset as AssetContract;
use Statamic\Contracts\Entries\Entry;
use Statamic\Extensions\Pagination\LengthAwarePaginator as StatamicPaginator;
use Statamic\Facades\Blink;
use Statamic\Facades\Entry as EntryFacade;
use Statamic\Facades\Term;
use Statamic\Modifiers\Modify;
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
        'episode' => 'Episode',
    ];

    /**
     * Episodes aren't entries: they come from the podcast feed under this type.
     */
    public const EPISODE = 'episode';

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
        'episodes' => ['label' => 'Episodes', 'blueprint' => self::EPISODE],
        'makes' => ['label' => 'Makes', 'blueprint' => 'make'],
        'moments' => ['label' => 'Moments', 'blueprint' => 'moment'],
        'talks' => ['label' => 'Talks', 'blueprint' => 'talk'],
    ];

    public function __construct(public BlogSeries $series, public Podcast $podcast) {}

    /**
     * The Log filters worth offering: Episodes only shows up once there's a podcast feed.
     *
     * @return array<string, array{label: string, blueprint: string}>
     */
    public function filters(): array
    {
        return array_filter(self::FILTERS, fn (array $filter) => $filter['blueprint'] !== self::EPISODE || $this->podcast->enabled());
    }

    /**
     * @param  list<string>  $blueprints
     * @return Collection<int, array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, caption: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, minutes: ?int, event: ?string, links: list<array{label: string, url: string}>, photos: list<array{square: string, full: string, width: int, height: int, alt: string}>, models: list<array{url: string, filename: string}>, podcast: ?array{show: string, number: ?int, minutes: ?int, audio: ?string}}>
     */
    public function items(array $blueprints, ?int $limit = 5): Collection
    {
        $entryBlueprints = array_values(array_diff($blueprints, [self::EPISODE]));

        $entries = $entryBlueprints === [] ? collect() : EntryFacade::query()
            ->where('collection', self::COLLECTION)
            ->whereIn('blueprint', $entryBlueprints)
            ->whereStatus('published')
            ->get()
            ->map(fn (Entry $entry) => $this->item($entry));

        $episodes = in_array(self::EPISODE, $blueprints, true)
            ? $this->podcast->episodes()->map(fn (array $episode) => $this->episode($episode))
            : collect();

        return $entries
            ->concat($episodes)
            ->sortByDesc(fn (array $item) => $item['date']?->timestamp ?? 0)
            ->when($limit !== null, fn (Collection $items) => $items->take($limit))
            ->values();
    }

    /**
     * A page of the Log, built the way Statamic's collection tag paginates so the static generator writes every page.
     *
     * @param  list<string>  $blueprints
     */
    public function paginate(array $blueprints, int $perPage = 20): LengthAwarePaginator
    {
        $items = $this->items($blueprints, null);
        $page = Paginator::resolveCurrentPage();

        $paginator = app()->makeWith(StatamicPaginator::class, [
            'items' => $items->forPage($page, $perPage)->values(),
            'total' => $items->count(),
            'perPage' => $perPage,
            'currentPage' => $page,
            'options' => ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page'],
        ])->withQueryString();

        Blink::put('tag-paginator', $paginator);

        return $paginator;
    }

    /**
     * @param  array{guid: string, title: string, link: ?string, audio: ?string, date: Carbon, number: ?int, minutes: ?int, show: string}  $episode
     * @return array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, caption: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, minutes: ?int, event: ?string, links: list<array{label: string, url: string}>, photos: list<array{square: string, full: string, width: int, height: int, alt: string}>, models: list<array{url: string, filename: string}>, podcast: ?array{show: string, number: ?int, minutes: ?int, audio: ?string}}
     */
    public function episode(array $episode): array
    {
        return [
            'id' => 'episode-' . md5($episode['guid']),
            'type' => self::TYPES[self::EPISODE],
            'date' => $episode['date'],
            'title' => $episode['title'],
            'url' => $episode['link'] ?? $episode['audio'],
            'summary' => null,
            'caption' => null,
            'series' => null,
            'part' => null,
            'kind' => null,
            'note' => null,
            'minutes' => null,
            'event' => null,
            'links' => [],
            'photos' => [],
            'models' => [],
            'podcast' => [
                'show' => $episode['show'],
                'number' => $episode['number'],
                'minutes' => $episode['minutes'],
                'audio' => $episode['audio'],
            ],
        ];
    }

    /**
     * @return array{id: string, type: string, date: ?Carbon, title: string, url: ?string, summary: ?string, caption: ?string, series: ?string, part: ?int, kind: ?string, note: ?string, minutes: ?int, event: ?string, links: list<array{label: string, url: string}>, photos: list<array{square: string, full: string, width: int, height: int, alt: string}>, models: list<array{url: string, filename: string}>, podcast: ?array{show: string, number: ?int, minutes: ?int, audio: ?string}}
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
            'caption' => $this->inlineCaption($entry),
            'series' => $this->seriesTitle($entry),
            'part' => $this->series->part($entry),
            'kind' => $entry->blueprint()->handle() === 'talk' ? null : $this->kind($entry),
            'note' => filled($entry->value('note')) ? (string) $entry->value('note') : null,
            'minutes' => $this->readingMinutes($entry),
            'event' => $entry->blueprint()->handle() === 'talk' ? $this->kind($entry) : null,
            'links' => $this->links($entry),
            'photos' => $this->photos($entry),
            'models' => $this->models($entry),
            'podcast' => null,
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
        $kind = $entry->augmentedValue('kind')->value()?->label();

        if (filled($kind)) {
            return $kind;
        }

        $event = $entry->value('event');

        return is_string($event) && trim($event) !== '' ? trim($event) : null;
    }

    /**
     * @return list<array{square: string, full: string, width: int, height: int, alt: string}>
     */
    public function photos(Entry $entry): array
    {
        $photos = $entry->augmentedValue('photos')->value();

        return collect($photos instanceof AssetContract ? [$photos] : ($photos?->get() ?? []))
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
     * A make's 3D models, for the STLs worth spinning around.
     *
     * @return list<array{url: string, filename: string}>
     */
    public function models(Entry $entry): array
    {
        $models = $entry->augmentedValue('models')->value();

        return collect($models instanceof AssetContract ? [$models] : ($models?->get() ?? []))
            ->filter(fn ($asset) => strtolower($asset->extension()) === 'stl')
            ->map(fn ($asset) => [
                'url' => (string) $asset->url(),
                'filename' => $asset->basename(),
            ])
            ->values()
            ->all();
    }

    /**
     * Links worth surfacing straight from the Log, like a talk's slides and video.
     *
     * @return list<array{label: string, url: string}>
     */
    public function links(Entry $entry): array
    {
        if ($entry->blueprint()->handle() !== 'talk') {
            return [];
        }

        $slides = $entry->value('slides');
        $video = $entry->augmentedValue('video')->value();

        return array_values(array_filter([
            is_string($slides) && $slides !== '' ? ['label' => 'Slides', 'url' => $slides] : null,
            $video ? ['label' => 'Video', 'url' => (string) $video->url()] : null,
        ]));
    }

    /**
     * How long a post takes to read, at the same pace the article page uses.
     */
    public function readingMinutes(Entry $entry): ?int
    {
        if ($entry->blueprint()->handle() !== 'post') {
            return null;
        }

        $content = $entry->augmentedValue('content')->value();

        if (blank($content)) {
            return null;
        }

        return max(1, (int) Modify::value(collect($content)->all())->readTime(225)->fetch());
    }

    public function date(Entry $entry): ?Carbon
    {
        if ($entry->hasDate()) {
            return Carbon::instance($entry->date());
        }

        return Carbon::instance($entry->lastModified());
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

    /**
     * The caption shown whole in the Log, unless a description was written to stand in for it.
     */
    public function inlineCaption(Entry $entry): ?string
    {
        if (! in_array($entry->blueprint()->handle(), self::INLINE_CAPTIONS, true)) {
            return null;
        }

        $description = $entry->value('description');

        if (is_string($description) && trim($description) !== '') {
            return null;
        }

        return $this->caption($entry);
    }

    public function seriesTitle(Entry $entry): ?string
    {
        $slug = Arr::first(Arr::wrap($entry->value('series')));

        if (! is_string($slug) || $slug === '') {
            return null;
        }

        return Term::find("series::{$slug}")?->title();
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function glide(AssetContract $asset, array $params): string
    {
        return (string) Statamic::tag('glide')->params(['src' => $asset, ...$params])->fetch();
    }
}
