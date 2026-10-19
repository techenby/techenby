<?php

namespace Tests\Feature;

use App\Widgets\Drafts;
use App\Widgets\NowPage;
use App\Widgets\QuickCapture;
use App\Widgets\Shortcuts;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use Statamic\Widgets\Widget;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::all()->first()->makeSuper());
    }

    #[Test]
    public function quick_capture_links_to_a_new_entry_for_each_configured_blueprint_in_order(): void
    {
        $html = $this->render(QuickCapture::class, ['collection' => 'log', 'blueprints' => ['tip', 'post']]);

        $this->assertStringContainsString('blueprint=tip', $html);
        $this->assertStringContainsString('blueprint=post', $html);
        $this->assertStringNotContainsString('blueprint=talk', $html);
        $this->assertLessThan(strpos($html, 'blueprint=post'), strpos($html, 'blueprint=tip'));
    }

    #[Test]
    public function quick_capture_shows_every_blueprint_when_none_are_configured(): void
    {
        $html = $this->render(QuickCapture::class, ['collection' => 'log']);

        foreach (['post', 'tip', 'make', 'moment', 'talk'] as $blueprint) {
            $this->assertStringContainsString("blueprint={$blueprint}", $html);
        }
    }

    #[Test]
    public function quick_capture_renders_nothing_for_a_missing_collection(): void
    {
        $this->assertSame('', $this->render(QuickCapture::class, ['collection' => 'nope']));
    }

    #[Test]
    public function drafts_lists_unpublished_entries_most_recently_edited_first(): void
    {
        Entry::query()->where('collection', 'log')->get()->each->delete();

        $this->makeLogEntry('Older draft', published: false, updatedAt: now()->subDays(3));
        $this->makeLogEntry('Newer draft', published: false, updatedAt: now()->subHour());
        $this->makeLogEntry('Published post', published: true, updatedAt: now());

        $html = $this->render(Drafts::class, ['collection' => 'log']);

        $this->assertStringContainsString('Newer draft', $html);
        $this->assertStringContainsString('Older draft', $html);
        $this->assertStringNotContainsString('Published post', $html);
        $this->assertLessThan(strpos($html, 'Older draft'), strpos($html, 'Newer draft'));
    }

    #[Test]
    public function drafts_respects_the_limit(): void
    {
        Entry::query()->where('collection', 'log')->get()->each->delete();

        $this->makeLogEntry('First draft', published: false, updatedAt: now()->subDay());
        $this->makeLogEntry('Second draft', published: false, updatedAt: now());

        $html = $this->render(Drafts::class, ['collection' => 'log', 'limit' => 1]);

        $this->assertStringContainsString('Second draft', $html);
        $this->assertStringNotContainsString('First draft', $html);
    }

    #[Test]
    public function drafts_shows_an_empty_state(): void
    {
        Entry::query()->where('collection', 'log')->get()->each->delete();

        $this->assertStringContainsString('No drafts.', $this->render(Drafts::class, ['collection' => 'log']));
    }

    #[Test]
    public function now_page_is_fresh_within_the_stale_window(): void
    {
        Carbon::setTestNow('2026-10-19 12:00');

        $this->updatePage('/now', [
            'as_of' => '2026-10-09 00:00',
            'sections' => [
                ['id' => 'now-test-work', 'heading' => 'Test work', 'content' => 'Writing tests.'],
                ['id' => 'now-test-blank', 'heading' => '', 'content' => 'No heading.'],
            ],
        ]);

        $html = $this->render(NowPage::class, ['uri' => '/now', 'stale_after_days' => 30]);

        $this->assertStringContainsString('As of October 9, 2026', $html);
        $this->assertStringContainsString('text="10 days ago"', $html);
        $this->assertStringContainsString('color="green"', $html);
        $this->assertStringContainsString('Test work', $html);
    }

    #[Test]
    public function now_page_turns_amber_once_stale(): void
    {
        Carbon::setTestNow('2026-10-19 12:00');

        $this->updatePage('/now', ['as_of' => '2026-08-01 00:00']);

        $html = $this->render(NowPage::class, ['uri' => '/now', 'stale_after_days' => 30]);

        $this->assertStringContainsString('color="amber"', $html);
    }

    #[Test]
    public function now_page_handles_a_future_as_of_date_and_a_missing_one(): void
    {
        Carbon::setTestNow('2026-10-19 12:00');

        $this->updatePage('/now', ['as_of' => '2026-10-20 00:00']);
        $this->assertStringContainsString('text="in 1 day"', $this->render(NowPage::class, ['uri' => '/now']));

        $this->updatePage('/now', ['as_of' => null]);
        $this->assertStringContainsString('No "as of" date set', $this->render(NowPage::class, ['uri' => '/now']));
    }

    #[Test]
    public function now_page_renders_nothing_for_a_missing_page(): void
    {
        $this->assertSame('', $this->render(NowPage::class, ['uri' => '/does-not-exist']));
    }

    #[Test]
    public function shortcuts_link_to_globals_and_collections_and_skip_unknown_handles(): void
    {
        $html = $this->render(Shortcuts::class, ['globals' => ['andy', 'nope'], 'collections' => ['projects', 'nope']]);

        $this->assertStringContainsString('text="Andy"', $html);
        $this->assertStringContainsString('text="Projects"', $html);
        $this->assertStringContainsString('/globals/andy', $html);
        $this->assertStringContainsString('/collections/projects', $html);
    }

    #[Test]
    public function shortcuts_render_nothing_without_links(): void
    {
        $this->assertSame('', $this->render(Shortcuts::class, ['collections' => ['nope']]));
    }

    #[Test]
    public function the_dashboard_loads_with_every_configured_widget(): void
    {
        $this->get(cp_route('dashboard'))
            ->assertOk()
            ->assertSee(['Quick capture', 'Now page', 'Drafts', 'Recent log entries', 'Shortcuts']);
    }

    /**
     * @param  class-string<Widget>  $class
     * @param  array<string, mixed>  $config
     */
    private function render(string $class, array $config): string
    {
        $widget = new $class;
        $widget->setConfig($config);

        return (string) $widget->html();
    }

    private function makeLogEntry(string $title, bool $published, Carbon $updatedAt): void
    {
        Entry::make()
            ->collection('log')
            ->blueprint('post')
            ->slug(str($title)->slug())
            ->date(now()->subWeek())
            ->published($published)
            ->data(['title' => $title, 'updated_at' => $updatedAt->timestamp])
            ->saveQuietly();
    }
}
