<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Statamic\Facades\Entry;

class FriendsOpmlController extends Controller
{
    /**
     * Build an OPML file of every friend's RSS feed from the Friends page.
     */
    public function __invoke(): Response
    {
        $page = Entry::findByUri('/friends');

        abort_if($page === null, 404);

        $feeds = collect($page->value('groups') ?? [])
            ->flatMap(fn (array $group) => $group['friends'] ?? [])
            ->filter(fn (array $friend) => filled($friend['name'] ?? null) && filled($friend['rss'] ?? null))
            ->unique('rss')
            ->values();

        return response()
            ->view('feeds.friends-opml', [
                'title' => 'Andy Swick’s friends',
                'feeds' => $feeds,
                'updatedAt' => now(),
            ])
            ->header('Content-Type', 'text/x-opml; charset=UTF-8');
    }
}
