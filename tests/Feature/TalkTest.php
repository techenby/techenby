<?php

namespace Tests\Feature;

use App\Support\Lately;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Tests\TestCase;

class TalkTest extends TestCase
{
    private ?EntryContract $talk = null;

    protected function tearDown(): void
    {
        $this->talk?->delete();

        parent::tearDown();
    }

    #[Test]
    public function a_talk_page_embeds_the_video_and_links_the_slides(): void
    {
        $this->makeTalk();

        $this->get('/log/talk-test')
            ->assertOk()
            ->assertSeeInOrder(['Test Event 2026', 'Talk test', 'A talk about testing.'], false)
            ->assertSee('src="https://www.youtube-nocookie.com/embed/sDEohTrNVvc?list=PLUA0-roTCM999jd102QKYtdOZjzqWpSQ4"', false)
            ->assertSee('href="https://www.youtube.com/watch?v=sDEohTrNVvc&amp;list=PLUA0-roTCM999jd102QKYtdOZjzqWpSQ4"', false)
            ->assertSee('href="https://github.com/techenby/livewire-best-practices-talk"', false)
            ->assertSee('Notes from the day.')
            ->assertSee('<footer', false);
    }

    #[Test]
    public function talks_get_their_own_log_filter_with_the_event_as_a_badge(): void
    {
        $this->makeTalk();

        $this->get('/log/talks')
            ->assertOk()
            ->assertSeeInOrder(['Talk', 'Test Event 2026', 'Talk test'])
            ->assertSee('href="/log/talk-test"', false);

        $this->assertSame('Talk', app(Lately::class)->type($this->talk));
    }

    private function makeTalk(): void
    {
        $this->talk = Entry::make()
            ->collection('log')
            ->blueprint('talk')
            ->slug('talk-test')
            ->date(now()->subMinute())
            ->data([
                'title' => 'Talk test',
                'event' => 'Test Event 2026',
                'description' => 'A talk about testing.',
                'video' => 'https://www.youtube.com/watch?v=sDEohTrNVvc&list=PLUA0-roTCM999jd102QKYtdOZjzqWpSQ4',
                'slides' => 'https://github.com/techenby/livewire-best-practices-talk',
                'content' => 'Notes from the day.',
            ]);

        $this->talk->save();
    }
}
