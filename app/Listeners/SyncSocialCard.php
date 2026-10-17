<?php

namespace App\Listeners;

use App\Support\SocialCard;
use Statamic\Events\EntryDeleted;
use Statamic\Events\EntrySaved;

class SyncSocialCard
{
    public function __construct(public SocialCard $socialCard) {}

    public function handle(EntrySaved|EntryDeleted $event): void
    {
        $entry = $event->entry;

        if (! $this->socialCard->supports($entry)) {
            return;
        }

        if ($event instanceof EntrySaved && $entry->published()) {
            $this->socialCard->generate($entry);

            return;
        }

        $this->socialCard->delete($entry);
    }
}
