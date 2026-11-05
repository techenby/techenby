<?php

namespace App\Http\Controllers;

use App\Support\SocialCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Statamic\Contracts\Entries\Entry;
use Statamic\CP\LivePreview;
use Statamic\Tokens\Handlers\LivePreview as LivePreviewHandler;

class SocialCardPreviewController extends Controller
{
    /**
     * Draw the social card for the entry open in Live Preview, unsaved edits included.
     */
    public function __invoke(Request $request, LivePreview $livePreview, SocialCard $socialCard): Response
    {
        $token = $request->statamicToken();

        abort_unless($token?->handler() === LivePreviewHandler::class, 404);

        $entry = $livePreview->item($token);

        abort_unless($entry instanceof Entry && $socialCard->supports($entry), 404, 'This entry doesn’t get a social card.');

        return response($socialCard->draw($entry))
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store');
    }
}
