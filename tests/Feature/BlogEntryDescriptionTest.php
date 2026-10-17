<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Tests\TestCase;

class BlogEntryDescriptionTest extends TestCase
{
    private ?EntryContract $createdEntry = null;

    protected function tearDown(): void
    {
        $this->createdEntry?->delete();

        parent::tearDown();
    }

    #[Test]
    public function blog_entry_with_a_description_has_meta_description_tags(): void
    {
        $this->makeEntry('description-test-post', ['description' => 'A post about "quotes" & things.']);

        $this->get('/log/description-test-post')
            ->assertOk()
            ->assertSee('<meta name="description" content="A post about &quot;quotes&quot; &amp; things.">', false)
            ->assertSee('<meta property="og:description" content="A post about &quot;quotes&quot; &amp; things.">', false);
    }

    #[Test]
    public function blog_entry_without_a_description_has_no_meta_description(): void
    {
        $this->makeEntry('no-description-test-post');

        $this->get('/log/no-description-test-post')
            ->assertOk()
            ->assertDontSee('<meta name="description"', false);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $slug, array $data = []): void
    {
        $this->createdEntry = Entry::make()
            ->collection('log')
            ->blueprint('post')
            ->slug($slug)
            ->published(true)
            ->data(['title' => 'Description test', ...$data]);

        $this->createdEntry->save();
    }
}
