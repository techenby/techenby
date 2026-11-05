<?php

namespace Tests\Feature;

use App\Support\SocialCard;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Statamic\CP\LivePreview;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;
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
        $entry = $this->makeEntry('post', 'social-card-test-post', ['title' => 'Social card test post']);

        $path = app(SocialCard::class)->path($entry);

        Storage::disk('social_cards')->assertExists($path);

        [$width, $height] = getimagesizefromstring(Storage::disk('social_cards')->get($path));

        $this->assertSame([SocialCard::WIDTH, SocialCard::HEIGHT], [$width, $height]);
    }

    #[Test]
    public function a_new_title_replaces_the_old_card(): void
    {
        $entry = $this->makeEntry('tip', 'social-card-test-tip', ['title' => 'First title']);
        $firstPath = app(SocialCard::class)->path($entry);

        $entry->set('title', 'Second title')->save();

        Storage::disk('social_cards')->assertMissing($firstPath);
        Storage::disk('social_cards')->assertExists(app(SocialCard::class)->path($entry));
        $this->assertCount(1, Storage::disk('social_cards')->files());
    }

    #[Test]
    public function unpublishing_or_deleting_an_entry_removes_its_card(): void
    {
        $entry = $this->makeEntry('post', 'social-card-test-unpublish', ['title' => 'Unpublish me']);

        $entry->published(false)->save();

        $this->assertSame([], Storage::disk('social_cards')->files());

        $entry->published(true)->save();
        $entry->delete();

        $this->assertSame([], Storage::disk('social_cards')->files());
    }

    #[Test]
    public function drafts_and_other_collections_get_no_card(): void
    {
        $this->makeEntry('post', 'social-card-test-draft', ['title' => 'Draft'], published: false);
        $this->makeEntry('project', 'social-card-test-project', ['title' => 'Project'], collection: 'projects');

        $this->assertSame([], Storage::disk('social_cards')->files());
    }

    #[Test]
    public function the_card_is_labelled_with_the_series_or_the_entry_type(): void
    {
        $socialCard = app(SocialCard::class);

        Term::make('social-card-test-series')->taxonomy('series')->blueprint('series')->data(['title' => 'Social Card Test Series'])->save();

        $post = $this->makeEntry('post', 'social-card-test-series', ['title' => 'Series post', 'series' => 'social-card-test-series', 'series_part' => 3]);
        $tip = $this->makeEntry('tip', 'social-card-test-label-tip', ['title' => 'Label tip']);

        $this->assertSame('Social Card Test Series · Part 3', $socialCard->label($post));
        $this->assertSame('Tip', $socialCard->label($tip));
    }

    #[Test]
    public function makes_are_labelled_with_their_kind_and_moments_as_moments(): void
    {
        $socialCard = app(SocialCard::class);

        $printed = $this->makeEntry('make', 'social-card-test-printed', ['title' => 'Printed thing', 'kind' => '3d-print']);
        $make = $this->makeEntry('make', 'social-card-test-make', ['title' => 'Some make']);
        $moment = $this->makeEntry('moment', 'social-card-test-label-moment', ['title' => 'Some moment']);

        $this->assertSame('3D print', $socialCard->label($printed));
        $this->assertSame('Make', $socialCard->label($make));
        $this->assertSame('Moment', $socialCard->label($moment));
    }

    #[Test]
    public function make_and_moment_pages_point_link_previews_at_their_card(): void
    {
        foreach (['make', 'moment'] as $blueprint) {
            $entry = $this->makeEntry($blueprint, "social-card-test-{$blueprint}-meta", ['title' => "A {$blueprint}"]);

            $this->get("/log/social-card-test-{$blueprint}-meta")
                ->assertOk()
                ->assertSee('<meta property="og:image" content="' . Storage::disk('social_cards')->url(app(SocialCard::class)->path($entry)) . '">', false);
        }
    }

    #[Test]
    public function a_new_design_version_gives_every_card_a_new_file_name(): void
    {
        $entry = $this->makeEntry('tip', 'social-card-test-version', ['title' => 'Versioned']);

        $this->assertSame(
            $entry->id() . '-' . substr(md5(SocialCard::DESIGN_VERSION . '|Tip|Versioned'), 0, 8) . '.png',
            app(SocialCard::class)->path($entry),
        );
    }

    #[Test]
    public function article_pages_point_link_previews_at_their_card_and_draw_it_if_missing(): void
    {
        $entry = $this->makeEntry('post', 'social-card-test-meta', ['title' => 'Meta test post']);
        $path = app(SocialCard::class)->path($entry);

        Storage::disk('social_cards')->delete($path);

        $this->get('/log/social-card-test-meta')
            ->assertOk()
            ->assertSee('<meta property="og:title" content="Meta test post">', false)
            ->assertSee('<meta property="og:image" content="' . Storage::disk('social_cards')->url($path) . '">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);

        Storage::disk('social_cards')->assertExists($path);
    }

    #[Test]
    public function pages_get_an_unlabelled_card(): void
    {
        $socialCard = app(SocialCard::class);
        $now = Entry::findByUri('/now');

        $this->assertTrue($socialCard->supports($now));
        $this->assertNull($socialCard->label($now));
        $this->assertSame('Now', $socialCard->title($now));
    }

    #[Test]
    public function the_home_page_card_shows_the_whole_headline_in_up_to_four_lines(): void
    {
        $socialCard = app(SocialCard::class);
        $home = Entry::findByUri('/');
        $headline = (string) $home->get('heading');

        $this->assertTrue($socialCard->supports($home));
        $this->assertSame($headline, $socialCard->title($home));

        $lines = $socialCard->titleLayout($headline, 4)['lines'];

        $this->assertLessThanOrEqual(4, count($lines));
        $this->assertSame($headline, implode(' ', $lines));
    }

    #[Test]
    public function a_title_too_long_for_its_lines_ends_in_an_ellipsis(): void
    {
        $title = str_repeat('A really very long social card title ', 12);

        $lines = app(SocialCard::class)->titleLayout($title)['lines'];

        $this->assertCount(3, $lines);
        $this->assertStringEndsWith('…', end($lines));
    }

    #[Test]
    public function pages_point_link_previews_at_their_card(): void
    {
        $pages = ['/now', '/uses', '/projects', '/faq', '/how-i-work', '/newsletter', '/log', '/log/writing'];

        foreach ($pages as $uri) {
            $entry = Entry::findByUri(str_starts_with($uri, '/log') ? '/log' : $uri);

            $this->get($uri)
                ->assertOk()
                ->assertSee('<meta property="og:title" content="' . e($entry->get('title')) . '">', false)
                ->assertSee('<meta property="og:image" content="' . Storage::disk('social_cards')->url(app(SocialCard::class)->path($entry)) . '">', false);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('<meta property="og:title" content="Andy Swick">', false)
            ->assertSee('<meta property="og:image" content="' . Storage::disk('social_cards')->url(app(SocialCard::class)->path(Entry::findByUri('/'))) . '">', false);
    }

    #[Test]
    public function live_preview_draws_the_card_from_unsaved_edits(): void
    {
        $entry = $this->makeEntry('post', 'social-card-test-preview', ['title' => 'Saved title']);
        $token = app(LivePreview::class)->tokenize(null, $entry->set('title', 'Unsaved title'));

        $response = $this->get('/social-card-preview?token=' . $token->token())
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->assertSame(md5(app(SocialCard::class)->render('Writing', 'Unsaved title')), md5($response->getContent()));
    }

    #[Test]
    public function the_card_preview_needs_a_live_preview_of_an_entry_that_gets_a_card(): void
    {
        $this->get('/social-card-preview')->assertNotFound();
        $this->get('/social-card-preview?token=not-a-real-token')->assertNotFound();
        $this->get('/social-card-preview?token=' . app(LivePreview::class)->tokenize(null, Entry::query()->where('collection', 'projects')->first())->token())->assertNotFound();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $blueprint, string $slug, array $data, bool $published = true, string $collection = 'log'): \Statamic\Contracts\Entries\Entry
    {
        $entry = Entry::make()
            ->collection($collection)
            ->blueprint($blueprint)
            ->slug($slug)
            ->published($published)
            ->data($data);

        if ($entry->collection()->dated()) {
            $entry->date(now()->subDay());
        }

        $entry->save();

        $this->createdEntries[] = $entry;

        return $entry;
    }
}
