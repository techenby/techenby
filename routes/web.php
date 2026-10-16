<?php

use App\Support\Lately;
use Illuminate\Support\Facades\Route;

Route::statamic('log/{type}', 'log')->whereIn('type', array_keys(Lately::FILTERS));

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);
