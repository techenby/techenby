<?php

namespace App\Widgets;

use Illuminate\View\View;
use Statamic\Facades\Collection;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use Statamic\Widgets\Widget;

class Shortcuts extends Widget
{
    /**
     * Links to the configured global sets and collections the user can reach.
     */
    public function html(): View|string
    {
        $globals = collect($this->config('globals', []))
            ->map(fn (string $handle) => GlobalSet::findByHandle($handle)?->in(Site::selected()->handle()))
            ->filter(fn ($variables) => $variables && User::current()->can('edit', $variables->globalSet()))
            ->map(fn ($variables) => [
                'title' => $variables->globalSet()->title(),
                'icon' => 'globals',
                'url' => $variables->editUrl(),
            ]);

        $collections = collect($this->config('collections', []))
            ->map(fn (string $handle) => Collection::findByHandle($handle))
            ->filter(fn ($collection) => $collection && User::current()->can('view', $collection))
            ->map(fn ($collection) => [
                'title' => $collection->title(),
                'icon' => 'collections',
                'url' => $collection->showUrl(),
            ]);

        $links = $globals->merge($collections)->values();

        if ($links->isEmpty()) {
            return '';
        }

        return view('widgets.shortcuts', [
            'title' => $this->config('title', 'Shortcuts'),
            'links' => $links,
        ]);
    }
}
