<?php

namespace Tests\Feature;

use App\Support\Lately;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class LogFeedTest extends TestCase
{
    /**
     * @var list<\Statamic\Contracts\Entries\Entry>
     */
    private array $createdEntries = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeEntry('post', 'feed-test-post', [
            'title' => 'Feed test post',
            'description' => 'A post for the feed.',
        ], date: Carbon::parse('2026-09-12'));

        $this->makeEntry('tip', 'feed-test-tip', [
            'title' => 'Feed test tip',
            'content' => 'A tip for the feed.',
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
    public function the_log_feed_lists_every_type(): void
    {
        $response = $this->get('/log/feed')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8');

        $rss = simplexml_load_string($response->getContent());

        $this->assertNotFalse($rss);
        $this->assertSame('2.0', (string) $rss['version']);

        $titles = collect(iterator_to_array($rss->channel->item, false))->map(fn ($item) => (string) $item->title);

        $this->assertContains('Feed test post', $titles);
        $this->assertContains('Feed test tip', $titles);

        $post = collect(iterator_to_array($rss->channel->item, false))->first(fn ($item) => (string) $item->title === 'Feed test post');

        $this->assertSame(url('/log/feed-test-post'), (string) $post->link);
        $this->assertSame('A post for the feed.', (string) $post->description);
        $this->assertSame('Writing', (string) $post->category);
    }

    #[Test]
    public function each_filter_has_its_own_feed(): void
    {
        $response = $this->get('/log/tips/feed')->assertOk();

        $rss = simplexml_load_string($response->getContent());
        $titles = collect(iterator_to_array($rss->channel->item, false))->map(fn ($item) => (string) $item->title);

        $this->assertStringContainsString('Tips', (string) $rss->channel->title);
        $this->assertContains('Feed test tip', $titles);
        $this->assertNotContains('Feed test post', $titles);
    }

    #[Test]
    public function an_unknown_filter_has_no_feed(): void
    {
        $this->get('/log/nonsense/feed')->assertNotFound();
    }

    #[Test]
    public function drafts_stay_out_of_the_feed(): void
    {
        $this->makeEntry('post', 'feed-test-draft', ['title' => 'Feed test draft'], published: false);

        $this->get('/log/feed')
            ->assertOk()
            ->assertDontSee('Feed test draft');
    }

    #[Test]
    public function the_log_links_to_its_feeds(): void
    {
        $this->get('/log')
            ->assertOk()
            ->assertSee('href="/log/feed"', false)
            ->assertSee('Subscribe to everything');

        $this->get('/log/tips')
            ->assertOk()
            ->assertSee('href="/log/tips/feed"', false)
            ->assertSee('Subscribe to Tips');
    }

    #[Test]
    public function pages_advertise_the_log_feeds(): void
    {
        $siteFeedLink = '<link rel="alternate" type="application/rss+xml" title="Andy Swick’s Log" href="'.route('log.feed').'">';
        $tipsFeedLink = '<link rel="alternate" type="application/rss+xml" title="Andy Swick’s Log · Tips" href="'.route('log.feed.type', 'tips').'">';

        $this->get('/')
            ->assertOk()
            ->assertSee($siteFeedLink, false)
            ->assertDontSee($tipsFeedLink, false);

        $this->get('/log')
            ->assertOk()
            ->assertSee($siteFeedLink, false)
            ->assertDontSee($tipsFeedLink, false);

        $this->get('/log/tips')
            ->assertOk()
            ->assertSee($siteFeedLink, false)
            ->assertSee($tipsFeedLink, false);
    }

    #[Test]
    public function no_log_entry_uses_the_feed_slug(): void
    {
        $slugs = Entry::query()->where('collection', Lately::COLLECTION)->get()->map->slug();

        $this->assertNotContains('feed', $slugs);
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
