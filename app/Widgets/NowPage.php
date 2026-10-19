<?php

namespace App\Widgets;

use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use Statamic\Widgets\Widget;

class NowPage extends Widget
{
    /**
     * How fresh the now page is, with its section headings and an edit button.
     */
    public function html(): View|string
    {
        $page = Entry::findByUri($this->config('uri', '/now'));

        if (! $page || ! User::current()->can('edit', $page)) {
            return '';
        }

        $asOf = $page->get('as_of') ? Carbon::parse($page->get('as_of')) : null;
        $daysOld = $asOf ? (int) $asOf->startOfDay()->diffInDays(now()->startOfDay()) : null;

        return view('widgets.now_page', [
            'title' => $this->config('title', 'Now page'),
            'editUrl' => $page->editUrl(),
            'asOf' => $asOf,
            'daysOld' => $daysOld,
            'freshness' => $this->describeAge($daysOld),
            'isStale' => $daysOld === null || $daysOld > $this->config('stale_after_days', 30),
            'sections' => collect($page->get('sections', []))->pluck('heading')->filter()->values(),
        ]);
    }

    /**
     * A short label for how long ago the now page was updated.
     */
    private function describeAge(?int $daysOld): ?string
    {
        return match (true) {
            $daysOld === null => null,
            $daysOld === 0 => 'today',
            $daysOld < 0 => 'in ' . abs($daysOld) . ' ' . str('day')->plural(abs($daysOld)),
            default => $daysOld . ' ' . str('day')->plural($daysOld) . ' ago',
        };
    }
}
