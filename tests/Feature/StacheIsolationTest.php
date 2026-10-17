<?php

namespace Tests\Feature;

use Illuminate\Cache\ArrayStore;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Statamic\Facades\Stache;
use Statamic\Facades\Term;
use Tests\TestCase;

class StacheIsolationTest extends TestCase
{
    #[Test]
    public function new_entries_and_terms_are_saved_only_in_the_disposable_stache(): void
    {
        $this->assertInstanceOf(ArrayStore::class, Stache::cacheStore()->getStore());

        $entry = Entry::make()
            ->collection('log')
            ->blueprint('post')
            ->slug('stache-isolation-test')
            ->published(false)
            ->date(now()->subDay())
            ->data(['title' => 'Stache isolation test']);

        $term = Term::make()
            ->taxonomy('series')
            ->slug('stache-isolation-test')
            ->data(['title' => 'Stache isolation test']);

        $this->assertStringStartsWith($this->fakeStacheDirectory . '/', $entry->path());
        $this->assertStringStartsWith($this->fakeStacheDirectory . '/', $term->path());

        $entry->save();
        $term->save();

        $this->assertFileExists($entry->path());
        $this->assertFileExists($term->path());
        $this->assertNotNull(Entry::find($entry->id()));
        $this->assertNotNull(Term::find($term->id()));
        $this->assertFileDoesNotExist(base_path('content/collections/log/' . basename($entry->path())));
        $this->assertFileDoesNotExist(base_path('content/taxonomies/series/stache-isolation-test.yaml'));

        $entry->delete();
        $term->delete();

        $this->assertFileDoesNotExist($entry->path());
        $this->assertFileDoesNotExist($term->path());
    }

    #[Test]
    public function editing_and_deleting_existing_content_leaves_the_real_files_untouched(): void
    {
        $entry = Entry::findByUri('/');
        $term = Term::query()->first();

        $this->assertNotNull($entry);
        $this->assertNotNull($term);

        $originalEntryPath = base_path('content/collections/pages/' . basename($entry->path()));
        $originalTermPath = base_path('content/taxonomies/' . $term->taxonomyHandle() . '/' . basename($term->path()));
        $originalEntry = File::get($originalEntryPath);
        $originalTerm = File::get($originalTermPath);

        $this->assertStringStartsWith($this->fakeStacheDirectory . '/', $entry->path());
        $this->assertStringStartsWith($this->fakeStacheDirectory . '/', $term->path());

        $entry->set('title', 'Isolated page')->save();
        $term->set('title', 'Isolated term')->save();

        $this->assertStringContainsString('Isolated page', File::get($entry->path()));
        $this->assertStringContainsString('Isolated term', File::get($term->path()));
        $this->assertSame($originalEntry, File::get($originalEntryPath));
        $this->assertSame($originalTerm, File::get($originalTermPath));

        $entry->delete();
        $term->delete();

        $this->assertFileDoesNotExist($entry->path());
        $this->assertFileDoesNotExist($term->path());
        $this->assertSame($originalEntry, File::get($originalEntryPath));
        $this->assertSame($originalTerm, File::get($originalTermPath));
    }
}
