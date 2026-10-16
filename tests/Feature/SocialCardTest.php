<?php

namespace Tests\Feature;

use App\Support\SocialCard;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class SocialCardTest extends TestCase
{
    /**
     * @var list<\Statamic\Contracts\Entries\Entry>
     */
    private array $createdEntries = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('social_cards');
    }

    protected function tearDown(): void
    {
        foreach ($this->createdEntries as $entry) {
            $entry->delete();
        }

        parent::tearDown();
    }

    #[Test]
    public function saving_a_published_post_draws_its_card(): void
    {
        $entry = $this->makeEntry('blog', 'social-card-test-post', ['title' => 'Social card test post']);

        $path = app(SocialCard::class)->path($entry);

        Storage::disk('social_cards')->assertExists($path);

        [$width, $height] = getimagesizefromstring(Storage::disk('social_cards')->get($path));

        $this->assertSame([SocialCard::WIDTH, SocialCard::HEIGHT], [$width, $height]);
    }

    #[Test]
    public function a_new_title_replaces_the_old_card(): void
    {
        $entry = $this->makeEntry('tips', 'social-card-test-tip', ['title' => 'First title']);
        $firstPath = app(SocialCard::class)->path($entry);

        $entry->set('title', 'Second title')->save();

        Storage::disk('social_cards')->assertMissing($firstPath);
        Storage::disk('social_cards')->assertExists(app(SocialCard::class)->path($entry));
        $this->assertCount(1, Storage::disk('social_cards')->files());
    }

    #[Test]
    public function unpublishing_or_deleting_an_entry_removes_its_card(): void
    {
        $entry = $this->makeEntry('blog', 'social-card-test-unpublish', ['title' => 'Unpublish me']);

        $entry->published(false)->save();

        $this->assertSame([], Storage::disk('social_cards')->files());

        $entry->published(true)->save();
        $entry->delete();

        $this->assertSame([], Storage::disk('social_cards')->files());
    }

    #[Test]
    public function drafts_and_other_collections_get_no_card(): void
    {
        $this->makeEntry('blog', 'social-card-test-draft', ['title' => 'Draft'], published: false);
        $this->makeEntry('moments', 'social-card-test-moment', ['title' => 'Moment']);

        $this->assertSame([], Storage::disk('social_cards')->files());
    }

    #[Test]
    public function the_card_is_labelled_with_the_series_or_the_entry_type(): void
    {
        $socialCard = app(SocialCard::class);

        $post = $this->makeEntry('blog', 'social-card-test-series', ['title' => 'Series post', 'series' => 'sunny-goes-native', 'series_part' => 3]);
        $tip = $this->makeEntry('tips', 'social-card-test-label-tip', ['title' => 'Label tip']);

        $this->assertSame('Sunny Goes Native · Part 3', $socialCard->label($post));
        $this->assertSame('Tip', $socialCard->label($tip));
    }

    #[Test]
    public function article_pages_point_link_previews_at_their_card_and_draw_it_if_missing(): void
    {
        $entry = $this->makeEntry('blog', 'social-card-test-meta', ['title' => 'Meta test post']);
        $path = app(SocialCard::class)->path($entry);

        Storage::disk('social_cards')->delete($path);

        $this->get('/blog/social-card-test-meta')
            ->assertOk()
            ->assertSee('<meta property="og:title" content="Meta test post">', false)
            ->assertSee('<meta property="og:image" content="'.Storage::disk('social_cards')->url($path).'">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);

        Storage::disk('social_cards')->assertExists($path);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $collection, string $slug, array $data, bool $published = true): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection($collection)
            ->blueprint(['blog' => 'blog', 'tips' => 'tip', 'moments' => 'moment'][$collection])
            ->slug($slug)
            ->published($published)
            ->date(now()->subDay())
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
