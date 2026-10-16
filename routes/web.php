<?php

use App\Http\Controllers\FriendsOpmlController;
use App\Http\Controllers\LogFeedController;
use App\Support\Lately;
use Illuminate\Support\Facades\Route;

Route::get('friends.opml', FriendsOpmlController::class)->name('friends.opml');
Route::get('log/feed', LogFeedController::class)->name('log.feed');
Route::get('log/{type}/feed', LogFeedController::class)->whereIn('type', array_keys(Lately::FILTERS))->name('log.feed.type');

Route::statamic('log/{type}', 'log')->whereIn('type', array_keys(Lately::FILTERS));

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);
