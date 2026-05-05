<?php

use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);
