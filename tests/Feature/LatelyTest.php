<?php

namespace Tests\Feature;

use App\Support\Lately;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class LatelyTest extends TestCase
{
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
    public function the_homepage_shows_recent_posts_and_tips(): void
    {
        $this->makeEntry('post', 'lately-test-post', [
            'title' => 'Lately test post',
            'description' => 'A description written for the homepage.',
            'series' => 'sunny-goes-native',
            'series_part' => 1,
        ], date: now()->subMinute());

        $this->makeEntry('tip', 'lately-test-tip', ['title' => 'Lately test tip'], date: now()->subMinutes(2));

        $this->get('/')
            ->assertOk()
            ->assertSee('id="lately-heading"', false)
            ->assertSee('Lately test post')
            ->assertSee('href="/log/lately-test-post"', false)
            ->assertSee('A description written for the homepage.')
            ->assertSee('Sunny Goes Native · Part 1')
            ->assertSee('Lately test tip');
    }

    #[Test]
    public function drafts_are_left_out(): void
    {
        $this->makeEntry('post', 'lately-test-draft', ['title' => 'Lately test draft'], published: false);

        $titles = app(Lately::class)->items(['post', 'tip'])->pluck('title');

        $this->assertNotContains('Lately test draft', $titles);
    }

    #[Test]
    public function items_are_newest_first_and_limited(): void
    {
        $this->makeEntry('post', 'lately-test-older', ['title' => 'Older'], date: now()->subMinutes(2));
        $this->makeEntry('tip', 'lately-test-newer', ['title' => 'Newer'], date: now()->subMinute());

        $items = app(Lately::class)->items(['post', 'tip'], limit: 2);

        $this->assertSame(['Newer', 'Older'], $items->pluck('title')->all());
        $this->assertSame(['Tip', 'Writing'], $items->pluck('type')->all());
    }

    #[Test]
    public function scheduled_entries_stay_hidden_until_their_date(): void
    {
        $this->makeEntry('post', 'lately-test-scheduled', ['title' => 'Lately test scheduled'], date: now()->addWeek());

        $titles = app(Lately::class)->items(['post', 'tip'])->pluck('title');

        $this->assertNotContains('Lately test scheduled', $titles);
    }

    #[Test]
    public function the_summary_falls_back_to_the_first_sentence_of_the_content(): void
    {
        $tip = $this->makeEntry('tip', 'lately-test-tip', [
            'title' => 'Tip',
            'content' => "Use `factories` sparingly. They are slower than you think.\n\nMore detail here.",
        ]);

        $this->assertSame('Use factories sparingly.', app(Lately::class)->summary($tip));
    }

    #[Test]
    public function no_collections_means_no_items(): void
    {
        $this->assertTrue(app(Lately::class)->items([])->isEmpty());
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
