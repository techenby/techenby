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
        $this->get('/log/writing')
            ->assertOk()
            ->assertSee('Log test post')
            ->assertDontSee('Log test tip');
    }

    #[Test]
    public function the_tips_filter_only_shows_tips(): void
    {
        $this->get('/log/tips')
            ->assertOk()
            ->assertSee('Log test tip')
            ->assertDontSee('Log test post');
    }

    #[Test]
    public function an_unknown_filter_is_not_found(): void
    {
        $this->get('/log/nonsense')->assertNotFound();
    }

    #[Test]
    public function filter_pages_share_the_log_heading_and_link_to_each_other(): void
    {
        $this->get('/log/writing')
            ->assertOk()
            ->assertSee('<h1', false)
            ->assertSee('href="/log"', false)
            ->assertSeeInOrder(['aria-current="page"', 'Writing'], false)
            ->assertSee('href="/log/moments"', false);
    }

    #[Test]
    public function makes_show_their_kind_photos_and_note_without_a_link(): void
    {
        $this->makeEntry('makes', 'log-test-make', [
            'title' => 'Log test make',
            'kind' => '3d-print',
            'note' => 'first try was stringy',
            'photos' => ['makes/puzzle/puzzle-01.jpg'],
        ], date: Carbon::parse('2026-07-20'));

        $response = $this->get('/log/makes')
            ->assertOk()
            ->assertSee('Log test make')
            ->assertSee('3D print')
            ->assertSee('first try was stringy')
            ->assertSee('aria-label="Photos of Log test make"', false)
            ->assertSee('alt="A finished jigsaw puzzle of an illustrated fantasy city by the sea, on a wooden table."', false)
            ->assertSeeInOrder(['<figcaption', 'A finished jigsaw puzzle of an illustrated fantasy city by the sea, on a wooden table.', '</figcaption>'], false)
            ->assertSee('/img/asset/', false)
            ->assertDontSee('Log test post')
            ->assertDontSee('Log test tip');

        $this->assertStringNotContainsString('>Log test make</a>', $response->getContent());
    }

    #[Test]
    public function makes_and_moments_show_their_full_caption_as_markdown(): void
    {
        $this->makeEntry('moments', 'log-test-caption', [
            'title' => 'Log test caption',
            'content' => "First sentence here. Second sentence with a [link](https://example.com).\n\n<script>alert(1)</script>\n\nAnother paragraph.",
        ], date: Carbon::parse('2026-07-22'));

        $this->get('/log/moments')
            ->assertOk()
            ->assertSee('Second sentence with a <a href="https://example.com">link</a>.', false)
            ->assertSee('<p>Another paragraph.</p>', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    #[Test]
    public function posts_only_show_the_first_sentence(): void
    {
        $this->makeEntry('tips', 'log-test-long-tip', [
            'title' => 'Log test long tip',
            'content' => 'The teaser sentence. The rest stays on the tip page.',
        ], date: Carbon::parse('2026-08-04'));

        $this->get('/log/tips')
            ->assertOk()
            ->assertSee('The teaser sentence.')
            ->assertDontSee('The rest stays on the tip page.');
    }

    #[Test]
    public function the_moments_filter_only_shows_moments(): void
    {
        $this->makeEntry('moments', 'log-test-moment', ['title' => 'Log test moment'], date: Carbon::parse('2026-07-21'));

        $this->get('/log/moments')
            ->assertOk()
            ->assertSee('Log test moment')
            ->assertSee('Moment')
            ->assertDontSee('Log test post');
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
            ->blueprint(['blog' => 'blog', 'tips' => 'tip', 'makes' => 'make', 'moments' => 'moment'][$collection])
            ->slug($slug)
            ->published($published)
            ->date($date ?? now()->subMinute())
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
