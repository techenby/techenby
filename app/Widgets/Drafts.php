<?php

namespace App\Widgets;

use Illuminate\View\View;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use Statamic\Widgets\Widget;

class Drafts extends Widget
{
    /**
     * Unpublished entries in the configured collection, most recently edited first.
     */
    public function html(): View|string
    {
        $collection = Collection::findByHandle($this->config('collection', 'log'));

        if (! $collection || ! User::current()->can('view', $collection)) {
            return '';
        }

        $drafts = Entry::query()
            ->where('collection', $collection->handle())
            ->where('published', false)
            ->get()
            ->sortByDesc(fn (EntryContract $entry) => $entry->lastModified())
            ->take($this->config('limit', 5))
            ->map(fn (EntryContract $entry) => [
                'title' => $entry->value('title'),
                'type' => $entry->blueprint()->title(),
                'edited' => $entry->lastModified()->diffForHumans(),
                'url' => $entry->editUrl(),
            ])
            ->values();

        return view('widgets.drafts', [
            'title' => $this->config('title', 'Drafts'),
            'collectionUrl' => $collection->showUrl(),
            'drafts' => $drafts,
        ]);
    }
}
