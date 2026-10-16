<?php

namespace App\Http\Controllers;

use App\Support\Lately;
use Illuminate\Http\Response;

class LogFeedController extends Controller
{
    /**
     * Build an RSS feed of the Log, or of one Log filter when a type is given.
     */
    public function __invoke(Lately $lately, ?string $type = null): Response
    {
        $filter = $type ? Lately::FILTERS[$type] ?? abort(404) : null;
        $blueprints = $filter ? [$filter['blueprint']] : array_column(Lately::FILTERS, 'blueprint');

        return response()
            ->view('feeds.log', [
                'title' => 'Andy Swick’s Log'.($filter ? ' · '.$filter['label'] : ''),
                'link' => url($type ? "/log/{$type}" : '/log'),
                'self' => url($type ? "/log/{$type}/feed" : '/log/feed'),
                'items' => $lately->items($blueprints, 30),
            ])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
