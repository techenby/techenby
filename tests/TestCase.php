<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Statamic\Facades\Stache;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use PreventsSavingStacheItemsToDisk;

    protected string $fakeStacheDirectory;

    /**
     * Seed disposable Stache stores with real content so every page route exists.
     * Tests should create or override the data they assert on rather than rely on the real copy.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['statamic.stache.cache_store' => 'array']);

        $this->fakeStacheDirectory = sys_get_temp_dir() . '/techenby-tests-' . Str::uuid();

        $this->beforeApplicationDestroyed(function (): void {
            File::deleteDirectory($this->fakeStacheDirectory);
        });

        $directories = Stache::stores()->except('assets')->map->directory()->unique();

        $this->preventSavingStacheItemsToDisk();

        foreach ($directories as $key => $directory) {
            if (File::isDirectory($directory)) {
                File::copyDirectory($directory, Stache::store($key)->directory());
            }
        }
    }

    /**
     * Swap in test data on an existing page so assertions don't depend on the real copy.
     *
     * @param  array<string, mixed>  $data
     */
    protected function updatePage(string $uri, array $data): EntryContract
    {
        $page = Entry::query()->where('collection', 'pages')->get()->first(fn (EntryContract $entry) => $entry->uri() === $uri);

        $page->merge($data)->save();

        return $page;
    }
}
