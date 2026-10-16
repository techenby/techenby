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

        Storage::fake('local');
    }

    protected function tearDown(): void
    {
        foreach ($this->createdEntries as $entry) {
            $entry->delete();
        }

        parent::tearDown();
    }

    #[Test]
    public function a_published_post_has_a_social_card_image(): void
    {
        $this->makeEntry('blog', 'social-card-test-post', ['title' => 'Social card test post']);

        $response = $this->get('/social-cards/blog/social-card-test-post.png')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        [$width, $height] = getimagesizefromstring($response->getContent());

        $this->assertSame([SocialCard::WIDTH, SocialCard::HEIGHT], [$width, $height]);
        $this->assertCount(1, Storage::disk('local')->files('social-cards'));
    }

    #[Test]
    public function the_card_is_rendered_again_when_the_title_changes(): void
    {
        $entry = $this->makeEntry('tips', 'social-card-test-tip', ['title' => 'First title']);

        $this->get('/social-cards/tips/social-card-test-tip.png')->assertOk();

        $entry->set('title', 'Second title')->save();

        $this->get('/social-cards/tips/social-card-test-tip.png')->assertOk();

        $this->assertCount(2, Storage::disk('local')->files('social-cards'));
    }

    #[Test]
    public function drafts_and_other_collections_have_no_social_card(): void
    {
        $this->makeEntry('blog', 'social-card-test-draft', ['title' => 'Draft'], published: false);

        $this->get('/social-cards/blog/social-card-test-draft.png')->assertNotFound();
        $this->get('/social-cards/blog/does-not-exist.png')->assertNotFound();
        $this->get('/social-cards/makes/winter-village.png')->assertNotFound();
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
    public function article_pages_point_link_previews_at_their_card(): void
    {
        $this->makeEntry('blog', 'social-card-test-meta', ['title' => 'Meta test post']);

        $this->get('/blog/social-card-test-meta')
            ->assertOk()
            ->assertSee('<meta property="og:title" content="Meta test post">', false)
            ->assertSee('<meta property="og:image" content="'.route('social-card', ['blog', 'social-card-test-meta']).'">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $collection, string $slug, array $data, bool $published = true): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection($collection)
            ->blueprint($collection === 'blog' ? 'blog' : 'tip')
            ->slug($slug)
            ->published($published)
            ->date(now()->subDay())
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
