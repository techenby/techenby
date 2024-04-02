<?php

namespace App\Listeners;

use Illuminate\Support\Arr;
use Statamic\Events\EntrySaving;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Statamic\Facades\Asset;

class DownloadPhotoForLink
{
    public function handle(EntrySaving $event): void
    {
        if ($event->entry->blueprint->handle === 'link' && $event->entry->photo === null) {
            $data = $this->scrapeDataFromUrl($event->entry->link_url);

            $filename = Str::afterLast($data['image'], '/');
            $contents = file_get_contents($data['image']);
            Storage::disk('assets')->put('images/' . $filename, $contents);

            $event->entry->photo = 'images/' . $filename;
        }
    }

    private function scrapeDataFromUrl($url)
    {
        $data = [];
        $dom = new \DOMDocument();

        if (@$dom->loadHTMLFile($url)) {
            foreach($dom->getElementsByTagName('meta') as $meta) {
                if ($meta->getAttribute('property') == 'og:image') {
                    $data['image'] = $meta->getAttribute('content');
                }
            }

            $elements = $dom->getElementsByTagName('title');

            if ($elements->length > 0) {
                $data['title'] = $elements->item(0)->textContent;
            }
        }

        return $data;
    }
}
