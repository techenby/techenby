<?php

namespace App\Widgets;

use Illuminate\View\View;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Collection;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use Statamic\Fields\Blueprint;
use Statamic\Widgets\Widget;

class QuickCapture extends Widget
{
    /**
     * One "new entry" button per visible blueprint of the configured collection.
     */
    public function html(): View|string
    {
        $collection = Collection::findByHandle($this->config('collection', 'log'));

        if (! $collection || ! User::current()->can('create', [EntryContract::class, $collection])) {
            return '';
        }

        $order = $this->config('blueprints', []);

        $blueprints = $collection->entryBlueprints()
            ->reject->hidden()
            ->when($order, fn ($blueprints) => $blueprints
                ->filter(fn (Blueprint $blueprint) => in_array($blueprint->handle(), $order))
                ->sortBy(fn (Blueprint $blueprint) => array_search($blueprint->handle(), $order)))
            ->map(fn (Blueprint $blueprint) => [
                'title' => $blueprint->title(),
                'url' => cp_route('collections.entries.create', [$collection->handle(), Site::selected()->handle(), 'blueprint' => $blueprint->handle()]),
            ])
            ->values();

        return view('widgets.quick_capture', [
            'title' => $this->config('title', 'Quick capture'),
            'collectionUrl' => $collection->showUrl(),
            'blueprints' => $blueprints,
        ]);
    }
}
