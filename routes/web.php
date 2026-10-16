<?php

use App\Http\Controllers\FriendsOpmlController;
use App\Support\Lately;
use Illuminate\Support\Facades\Route;

Route::get('friends.opml', FriendsOpmlController::class)->name('friends.opml');

Route::statamic('log/{type}', 'log')->whereIn('type', array_keys(Lately::FILTERS));

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);
