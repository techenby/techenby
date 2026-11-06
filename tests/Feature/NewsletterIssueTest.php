<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Tests\TestCase;

class NewsletterIssueTest extends TestCase
{
    /**
     * @var list<EntryContract>
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
    public function an_issue_shows_its_subject_date_and_body(): void
    {
        $this->makeIssue('issue-test', [
            'title' => 'Issue test subject',
            'description' => 'An issue for the issue page.',
            'content' => "Hey y’all!\n\n## First story\n\nSome words.\n\n## Second story\n\n[A link](/log)",
        ], Carbon::parse('2026-10-02'));

        $this->get('/newsletter/issue-test')
            ->assertOk()
            ->assertSee('<meta name="description" content="An issue for the issue page.">', false)
            ->assertSeeInOrder(['Enby·Mail', 'Issue test subject', 'Oct 2, 2026', 'min read'])
            ->assertSee('<h2 id="first-story">First story</h2>', false)
            ->assertSeeInOrder(['On this page', 'href="#first-story"', 'href="#second-story"'], false)
            ->assertSee('<a href="/log">A link</a>', false)
            ->assertSee('href="/newsletter#past-issues"', false)
            ->assertSee('action="https://track.bentonow.com/forms/', false);
    }

    #[Test]
    public function the_newsletter_page_lists_published_issues_newest_first(): void
    {
        $this->makeIssue('issue-test-older', ['title' => 'Issue test older'], Carbon::parse('2026-10-02'));
        $this->makeIssue('issue-test-newer', ['title' => 'Issue test newer'], Carbon::parse('2026-10-09'));
        $this->makeIssue('issue-test-draft', ['title' => 'Issue test draft'], Carbon::parse('2026-10-16'), published: false);
        $this->makeIssue('issue-test-scheduled', ['title' => 'Issue test scheduled'], now()->addWeek());

        $this->get('/newsletter')
            ->assertOk()
            ->assertSeeInOrder([
                'id="past-issues"',
                'Oct 9, 2026', 'href="/newsletter/issue-test-newer"', 'Issue test newer',
                'Oct 2, 2026', 'href="/newsletter/issue-test-older"', 'Issue test older',
            ], false)
            ->assertDontSee('Issue test draft')
            ->assertDontSee('Issue test scheduled');
    }

    #[Test]
    public function the_newsletter_page_hides_past_issues_when_there_are_none(): void
    {
        Entry::query()->where('collection', 'newsletter')->get()->each->delete();

        $this->get('/newsletter')
            ->assertOk()
            ->assertDontSee('Past issues');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeIssue(string $slug, array $data, Carbon $date, bool $published = true): void
    {
        $entry = Entry::make()
            ->collection('newsletter')
            ->blueprint('issue')
            ->slug($slug)
            ->published($published)
            ->date($date)
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;
    }
}
