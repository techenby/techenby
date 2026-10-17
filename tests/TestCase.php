<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Facades\Stache;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use PreventsSavingStacheItemsToDisk;

    protected string $fakeStacheDirectory;

    /**
     * Seed disposable Stache stores with real content for page assertions.
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
}
