<?php

namespace Tests\Feature;

use App\Support\Lately;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Asset;
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

        Storage::fake('assets');
        Storage::disk('assets')->putFileAs('makes/test', UploadedFile::fake()->image('photo.jpg', 40, 30), 'photo.jpg');
        Asset::find('assets::makes/test/photo.jpg')->set('alt', 'A test photo of a finished make.')->save();

        $this->makeEntry('post', 'log-test-post', [
            'title' => 'Log test post',
            'description' => 'A post for the log.',
        ], date: Carbon::parse('2026-09-12'));

        $this->makeEntry('tip', 'log-test-tip', [
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
    public function writing_rows_show_their_reading_time_but_tips_do_not(): void
    {
        $this->makeEntry('post', 'log-test-long-post', [
            'title' => 'Log test long post',
            'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => str_repeat('word ', 700)]]]],
        ], date: Carbon::parse('2026-09-13'));

        $this->get('/log/writing')
            ->assertOk()
            ->assertSeeInOrder(['Writing', 'Sep 13', 'Log test long post', '4 min read']);

        $this->get('/log/tips')
            ->assertOk()
            ->assertDontSee('min read');
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
    public function makes_show_their_kind_photos_and_note_and_link_to_their_page(): void
    {
        $this->makeEntry('make', 'log-test-make', [
            'title' => 'Log test make',
            'kind' => '3d-print',
            'note' => 'first try was stringy',
            'photos' => ['makes/test/photo.jpg'],
        ], date: Carbon::parse('2026-07-20'));

        $response = $this->get('/log/makes')
            ->assertOk()
            ->assertSee('Log test make')
            ->assertSee('3D print')
            ->assertSee('first try was stringy')
            ->assertSee('aria-label="Photos of Log test make"', false)
            ->assertSee('alt="A test photo of a finished make."', false)
            ->assertSeeInOrder(['<figcaption', 'A test photo of a finished make.', '</figcaption>'], false)
            ->assertSee('/img/asset/', false)
            ->assertDontSee('Log test post')
            ->assertDontSee('Log test tip');

        $this->assertStringContainsString('href="/log/log-test-make"', $response->getContent());
    }

    #[Test]
    public function makes_and_moments_show_their_full_caption_as_markdown(): void
    {
        $this->makeEntry('moment', 'log-test-caption', [
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
        $this->makeEntry('tip', 'log-test-long-tip', [
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
        $this->makeEntry('moment', 'log-test-moment', ['title' => 'Log test moment'], date: Carbon::parse('2026-07-21'));

        $this->get('/log/moments')
            ->assertOk()
            ->assertSee('Log test moment')
            ->assertSee('Moment')
            ->assertDontSee('Log test post');
    }

    #[Test]
    public function drafts_and_scheduled_entries_are_hidden(): void
    {
        $this->makeEntry('post', 'log-test-draft', ['title' => 'Log test draft'], published: false);
        $this->makeEntry('post', 'log-test-scheduled', ['title' => 'Log test scheduled'], date: now()->addWeek());

        $this->get('/log')
            ->assertOk()
            ->assertDontSee('Log test draft')
            ->assertDontSee('Log test scheduled');
    }

    #[Test]
    public function moments_and_makes_have_their_own_pages(): void
    {
        $this->makeEntry('moment', 'log-test-moment-page', [
            'title' => 'Log test moment page',
            'content' => 'A moment worth keeping.',
            'photos' => ['makes/test/photo.jpg'],
        ]);

        $this->makeEntry('make', 'log-test-make-page', [
            'title' => 'Log test make page',
            'kind' => '3d-print',
            'note' => 'printed at the library',
            'content' => 'A make worth sharing.',
        ]);

        $this->get('/log/log-test-moment-page')
            ->assertOk()
            ->assertSee('<title>Log test moment page', false)
            ->assertSeeInOrder(['Moment', 'Log test moment page', 'alt="A test photo of a finished make."', 'A moment worth keeping.'], false)
            ->assertSee('aria-label="Photos of Log test moment page"', false)
            ->assertDontSee('min read');

        $this->get('/log/log-test-make-page')
            ->assertOk()
            ->assertSeeInOrder(['3D print', 'Log test make page', 'printed at the library', 'A make worth sharing.']);
    }

    #[Test]
    public function a_moment_description_is_used_for_link_previews_instead_of_the_caption(): void
    {
        $this->makeEntry('moment', 'log-test-moment-description', [
            'title' => 'Log test moment description',
            'description' => 'A preview written for link cards.',
            'content' => "*Light spoilers ahead.*\n\nThe rest of the caption.",
        ]);

        $this->get('/log/log-test-moment-description')
            ->assertOk()
            ->assertSee('<meta name="description" content="A preview written for link cards.">', false)
            ->assertDontSee('<meta name="description" content="Light spoilers ahead.">', false)
            ->assertSee('The rest of the caption.');

        $this->get('/log/moments')
            ->assertOk()
            ->assertSee('The rest of the caption.')
            ->assertDontSee('A preview written for link cards.');
    }

    #[Test]
    public function no_log_entry_uses_a_slug_reserved_for_a_filter_page(): void
    {
        $slugs = Entry::query()->where('collection', Lately::COLLECTION)->get()->map->slug();

        $this->assertEmpty($slugs->intersect(array_keys(Lately::FILTERS))->all());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $blueprint, string $slug, array $data, bool $published = true, ?Carbon $date = null): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection('log')
            ->blueprint($blueprint)
            ->slug($slug)
            ->published($published)
            ->date($date ?? now()->subMinute())
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
