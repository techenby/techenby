<?php

namespace Tests\Feature;

use App\Support\BlogSeries;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Taxonomy;
use Statamic\Facades\Term;
use Tests\TestCase;

class BlogSeriesTest extends TestCase
{
    /**
     * @var list<\Statamic\Contracts\Entries\Entry>
     */
    private array $createdEntries = [];

    private ?\Statamic\Contracts\Taxonomies\Term $createdTerm = null;

    protected function tearDown(): void
    {
        foreach ($this->createdEntries as $entry) {
            $entry->delete();
        }

        $this->createdTerm?->delete();

        parent::tearDown();
    }

    #[Test]
    public function series_taxonomy_is_attached_to_the_blog(): void
    {
        $this->assertNotNull(Taxonomy::find('series'));
        $this->assertContains('series', Collection::find('blog')->taxonomies()->map->handle()->all());
        $this->assertSame('Sunny Goes Native', Term::find('series::sunny-goes-native')?->title());
    }

    #[Test]
    public function entry_without_a_series_has_no_series(): void
    {
        $entry = $this->makeEntry('no-series-post', 'No series');

        $this->assertNull(app(BlogSeries::class)->for($entry));
    }

    #[Test]
    public function parts_are_ordered_by_part_number_with_previous_and_next(): void
    {
        $this->makeTerm();

        $partTwo = $this->makeEntry('test-series-part-2', 'Part two', ['series' => 'test-series', 'series_part' => 2]);
        $partZero = $this->makeEntry('test-series-part-0', 'Part zero', ['series' => 'test-series', 'series_part' => 0]);
        $partOne = $this->makeEntry('test-series-part-1', 'Part one', ['series' => 'test-series', 'series_part' => 1]);

        $series = app(BlogSeries::class)->for($partOne);

        $this->assertSame('Test Series', $series['title']);
        $this->assertSame(1, $series['part']);
        $this->assertSame(['Part zero', 'Part one', 'Part two'], $series['parts']->map->get('title')->all());
        $this->assertSame($partZero->id(), $series['previous']->id());
        $this->assertSame($partTwo->id(), $series['next']->id());
    }

    #[Test]
    public function unpublished_parts_are_hidden_except_the_current_entry(): void
    {
        $this->makeTerm();

        $published = $this->makeEntry('test-series-published', 'Published part', ['series' => 'test-series', 'series_part' => 0]);
        $this->makeEntry('test-series-other-draft', 'Other draft', ['series' => 'test-series', 'series_part' => 1], published: false);
        $draft = $this->makeEntry('test-series-draft', 'Current draft', ['series' => 'test-series', 'series_part' => 2], published: false);

        $series = app(BlogSeries::class)->for($draft);

        $this->assertSame(['Published part', 'Current draft'], $series['parts']->map->get('title')->all());
        $this->assertSame($published->id(), $series['previous']->id());
        $this->assertNull($series['next']);
    }

    #[Test]
    public function entry_page_shows_the_series_badge_and_parts(): void
    {
        $this->makeTerm();

        $this->makeEntry('test-series-page-0', 'Page part zero', ['series' => 'test-series', 'series_part' => 0]);
        $this->makeEntry('test-series-page-1', 'Page part one', ['series' => 'test-series', 'series_part' => 1]);

        $this->get('/blog/test-series-page-1')
            ->assertOk()
            ->assertSee('Test Series · Part 1')
            ->assertSee('A series · 2 parts so far')
            ->assertSee('href="/blog/test-series-page-0"', false)
            ->assertSeeInOrder(['Page part zero', 'aria-current="page"', 'Page part one', 'you are here'], false);
    }

    #[Test]
    public function the_first_part_of_a_series_still_shows_the_series_box(): void
    {
        $this->makeTerm();

        $this->makeEntry('test-series-only-part', 'Only part', ['series' => 'test-series', 'series_part' => 0]);

        $this->get('/blog/test-series-only-part')
            ->assertOk()
            ->assertSee('Test Series · Part 0')
            ->assertSee('A series · 1 part so far')
            ->assertSee('you are here');
    }

    private function makeTerm(): void
    {
        $this->createdTerm = Term::make('test-series')
            ->taxonomy('series')
            ->blueprint('series')
            ->data(['title' => 'Test Series']);

        $this->createdTerm->save();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $slug, string $title, array $data = [], bool $published = true): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection('blog')
            ->blueprint('blog')
            ->slug($slug)
            ->published($published)
            ->data(['title' => $title, ...$data]);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
