<?php

namespace Tests\Feature;

use App\Support\Lately;
use App\Support\Podcast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class EpisodesTest extends TestCase
{
    private const FEED_URL = 'https://feeds.transistor.fm/make-ship-happen';

    /**
     * @var list<\Statamic\Contracts\Entries\Entry>
     */
    private array $createdEntries = [];

    protected function tearDown(): void
    {
        foreach ($this->createdEntries as $entry) {
            $entry->delete();
        }

        parent::tearDown();
    }

    #[Test]
    public function without_a_feed_url_there_are_no_episodes_and_no_episodes_filter(): void
    {
        Http::preventStrayRequests();
        config(['services.transistor.feed_url' => null]);

        $this->assertTrue(app(Podcast::class)->episodes()->isEmpty());
        $this->assertArrayNotHasKey('episodes', app(Lately::class)->filters());

        $this->get('/log')
            ->assertOk()
            ->assertDontSee('href="/log/episodes"', false);

        $this->get('/log/episodes')->assertNotFound();
        $this->get('/log/episodes/feed')->assertNotFound();

        Http::assertNothingSent();
    }

    #[Test]
    public function it_reads_episodes_from_the_feed(): void
    {
        $this->fakeFeed();

        $episodes = app(Podcast::class)->episodes();

        $this->assertSame(['Shipping on a Sunday', 'Naming things & other hard problems', 'A trailer with no number'], $episodes->pluck('title')->all());

        $first = $episodes->first();

        $this->assertSame('b2a1c3d4-0012', $first['guid']);
        $this->assertSame('https://makeshiphappen.transistor.fm/episodes/shipping-on-a-sunday', $first['link']);
        $this->assertSame('https://media.transistor.fm/b2a1c3d4/shipping-on-a-sunday.mp3', $first['audio']);
        $this->assertSame(12, $first['number']);
        $this->assertSame(42, $first['minutes']);
        $this->assertSame('Make Ship Happen', $first['show']);
        $this->assertTrue($first['date']->equalTo(Carbon::parse('2026-10-03 14:00:00 -0500')));

        $this->assertSame([42, 62, 5], $episodes->pluck('minutes')->all());
        $this->assertNull($episodes->last()['number']);
        $this->assertNull($episodes->last()['link']);
    }

    #[Test]
    public function the_feed_is_cached(): void
    {
        $this->fakeFeed();

        app(Podcast::class)->episodes();
        app(Podcast::class)->episodes();

        Http::assertSentCount(1);
    }

    #[Test]
    public function a_timeout_means_no_episodes_rather_than_an_error(): void
    {
        config(['services.transistor.feed_url' => self::FEED_URL]);
        Http::fake(['*' => Http::failedConnection()]);

        $this->assertTrue(app(Podcast::class)->episodes()->isEmpty());

        $this->get('/log')->assertOk();
        $this->get('/log/episodes')->assertOk()->assertSee('Nothing here yet.');
    }

    #[Test]
    public function a_failed_response_means_no_episodes(): void
    {
        config(['services.transistor.feed_url' => self::FEED_URL]);
        Http::fake(['*' => Http::response('Server error', 500)]);

        $this->assertTrue(app(Podcast::class)->episodes()->isEmpty());
    }

    #[Test]
    public function bad_xml_means_no_episodes(): void
    {
        config(['services.transistor.feed_url' => self::FEED_URL]);
        Http::fake(['*' => Http::response('<rss><channel><item>', 200)]);

        $this->assertTrue(app(Podcast::class)->episodes()->isEmpty());

        $this->get('/log/feed')->assertOk();
    }

    #[Test]
    public function durations_turn_into_whole_minutes(): void
    {
        $podcast = app(Podcast::class);

        $this->assertSame(42, $podcast->minutes('2520'));
        $this->assertSame(42, $podcast->minutes('42:10'));
        $this->assertSame(62, $podcast->minutes('1:02:03'));
        $this->assertSame(1, $podcast->minutes('20'));
        $this->assertNull($podcast->minutes(''));
        $this->assertNull($podcast->minutes('soon'));
        $this->assertNull($podcast->minutes('0'));
    }

    #[Test]
    public function episodes_sort_in_with_entries_by_date(): void
    {
        $this->fakeFeed();

        $this->makeEntry('talk', 'episodes-test-talk', ['title' => 'Episodes test talk'], Carbon::parse('2026-09-12'));

        $items = app(Lately::class)->items(['talk', Lately::EPISODE], null)
            ->filter(fn (array $item) => $item['date']->between('2026-08-01', '2026-10-31'));

        $this->assertSame(['Shipping on a Sunday', 'Episodes test talk', 'Naming things & other hard problems'], $items->pluck('title')->values()->all());
        $this->assertSame(['Episode', 'Talk', 'Episode'], $items->pluck('type')->values()->all());
    }

    #[Test]
    public function the_log_offers_an_episodes_filter_once_there_is_a_feed(): void
    {
        $this->fakeFeed();

        $this->get('/log')
            ->assertOk()
            ->assertSeeInOrder(['href="/log/tips"', 'href="/log/episodes"', 'href="/log/makes"'], false);
    }

    #[Test]
    public function episode_rows_show_a_play_button_the_show_details_and_the_title(): void
    {
        $this->fakeFeed();

        $this->get('/log/episodes')
            ->assertOk()
            ->assertSeeInOrder(['October 2026', 'Episode', 'Oct 3', 'Shipping on a Sunday', 'August 2026', 'Naming things'])
            ->assertSee('href="https://media.transistor.fm/b2a1c3d4/shipping-on-a-sunday.mp3"', false)
            ->assertSee('Play Shipping on a Sunday')
            ->assertSee('Make Ship Happen · Episode 12 · 42 min')
            ->assertSee('href="https://makeshiphappen.transistor.fm/episodes/shipping-on-a-sunday"', false)
            ->assertSee('Make Ship Happen · 5 min')
            ->assertDontSee('Scheduled for later')
            ->assertDontSee('No date at all');
    }

    #[Test]
    public function the_log_is_paginated_across_entries_and_episodes(): void
    {
        $this->fakeFeed();

        $page = app(Lately::class)->paginate([Lately::EPISODE], 2);

        $this->assertSame(3, $page->total());
        $this->assertSame(2, $page->lastPage());
        $this->assertSame(['Shipping on a Sunday', 'Naming things & other hard problems'], $page->getCollection()->pluck('title')->all());

        $this->get('/log/episodes?page=2')->assertOk();
    }

    #[Test]
    public function episodes_have_their_own_feed_and_join_the_everything_feed(): void
    {
        $this->fakeFeed();

        $rss = simplexml_load_string($this->get('/log/episodes/feed')->assertOk()->getContent());
        $items = collect(iterator_to_array($rss->channel->item, false));

        $this->assertStringContainsString('Episodes', (string) $rss->channel->title);
        $this->assertSame(['Shipping on a Sunday', 'Naming things & other hard problems', 'A trailer with no number'], $items->map(fn ($item) => (string) $item->title)->all());
        $this->assertSame('https://makeshiphappen.transistor.fm/episodes/shipping-on-a-sunday', (string) $items->first()->link);
        $this->assertSame('Episode', (string) $items->first()->category);
        $this->assertSame('https://media.transistor.fm/b2a1c3d4/trailer.mp3', (string) $items->last()->link);

        $everything = collect(iterator_to_array(simplexml_load_string($this->get('/log/feed')->getContent())->channel->item, false))
            ->map(fn ($item) => (string) $item->title);

        $this->assertContains('Shipping on a Sunday', $everything);
    }

    #[Test]
    public function more_from_the_log_includes_episodes(): void
    {
        $this->fakeFeed();

        $this->makeEntry('post', 'episodes-test-post', ['title' => 'Episodes test post'], Carbon::parse('2026-01-01'));

        $this->assertContains('Episode', app(Lately::class)->items(array_keys(Lately::TYPES), null)->pluck('type'));

        $this->get('/log/episodes-test-post')
            ->assertOk()
            ->assertSee('More from the Log');
    }

    private function fakeFeed(): void
    {
        config(['services.transistor.feed_url' => self::FEED_URL]);

        Http::preventStrayRequests();
        Http::fake([self::FEED_URL => Http::response(file_get_contents(base_path('tests/fixtures/transistor-feed.xml')), 200, ['Content-Type' => 'application/rss+xml'])]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $blueprint, string $slug, array $data, Carbon $date): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection('log')
            ->blueprint($blueprint)
            ->slug($slug)
            ->published(true)
            ->date($date)
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
