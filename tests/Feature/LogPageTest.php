<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class LogPageTest extends TestCase
{
    /**
     * @var list<\Statamic\Contracts\Entries\Entry>
     */
    private array $createdEntries = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeEntry('blog', 'log-test-post', [
            'title' => 'Log test post',
            'description' => 'A post for the log.',
        ], date: Carbon::parse('2026-09-12'));

        $this->makeEntry('tips', 'log-test-tip', [
            'title' => 'Log test tip',
            'content' => 'A tip for the log. With more after.',
        ], date: Carbon::parse('2026-08-03'));
    }

    protected function tearDown(): void
    {
        foreach ($this->createdEntries as $entry) {
            $entry->delete();
        }

        parent::tearDown();
    }

    #[Test]
    public function it_lists_posts_and_tips_grouped_by_month(): void
    {
        $this->get('/log')
            ->assertOk()
            ->assertSee('<h1', false)
            ->assertSeeInOrder(['September 2026', 'Log test post', 'August 2026', 'Log test tip'])
            ->assertSee('A post for the log.')
            ->assertSee('A tip for the log.')
            ->assertSee('aria-current="page"', false);
    }

    #[Test]
    public function the_writing_filter_only_shows_posts(): void
    {
        $this->get('/log?type=writing')
            ->assertOk()
            ->assertSee('Log test post')
            ->assertDontSee('Log test tip');
    }

    #[Test]
    public function the_tips_filter_only_shows_tips(): void
    {
        $this->get('/log?type=tips')
            ->assertOk()
            ->assertSee('Log test tip')
            ->assertDontSee('Log test post');
    }

    #[Test]
    public function an_unknown_filter_shows_everything(): void
    {
        $this->get('/log?type=nonsense')
            ->assertOk()
            ->assertSee('Log test post')
            ->assertSee('Log test tip');
    }

    #[Test]
    public function drafts_and_scheduled_entries_are_hidden(): void
    {
        $this->makeEntry('blog', 'log-test-draft', ['title' => 'Log test draft'], published: false);
        $this->makeEntry('blog', 'log-test-scheduled', ['title' => 'Log test scheduled'], date: now()->addWeek());

        $this->get('/log')
            ->assertOk()
            ->assertDontSee('Log test draft')
            ->assertDontSee('Log test scheduled');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $collection, string $slug, array $data, bool $published = true, ?Carbon $date = null): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection($collection)
            ->blueprint($collection === 'blog' ? 'blog' : 'tip')
            ->slug($slug)
            ->published($published)
            ->date($date ?? now()->subMinute())
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
