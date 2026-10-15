<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\Entry as EntryFacade;
use Statamic\Facades\Term;

class BlogSeries
{
    /**
     * Find the series an entry belongs to, with its published parts in order.
     *
     * The current entry is always included, so a draft can be previewed in place.
     *
     * @return array{title: string, part: ?int, parts: Collection<int, Entry>, previous: ?Entry, next: ?Entry}|null
     */
    public function for(Entry $entry): ?array
    {
        $slug = Arr::first(Arr::wrap($entry->value('series')));

        if (! is_string($slug) || $slug === '') {
            return null;
        }

        $term = Term::find("series::{$slug}");

        if ($term === null) {
            return null;
        }

        $parts = EntryFacade::query()
            ->where('collection', $entry->collectionHandle())
            ->whereStatus('published')
            ->whereTaxonomy("series::{$slug}")
            ->get()
            ->reject(fn (Entry $part) => $part->id() === $entry->id())
            ->push($entry)
            ->sortBy(fn (Entry $part) => $part->value('series_part') ?? PHP_INT_MAX)
            ->values();

        $index = $parts->search(fn (Entry $part) => $part->id() === $entry->id());

        return [
            'title' => $term->title(),
            'part' => $this->part($entry),
            'parts' => $parts,
            'previous' => $index > 0 ? $parts->get($index - 1) : null,
            'next' => $parts->get($index + 1),
        ];
    }

    public function part(Entry $entry): ?int
    {
        $part = $entry->value('series_part');

        return is_numeric($part) ? (int) $part : null;
    }
}
