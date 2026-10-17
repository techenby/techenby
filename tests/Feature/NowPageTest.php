<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NowPageTest extends TestCase
{
    #[Test]
    public function the_now_page_shows_when_it_was_updated_and_each_section(): void
    {
        $this->updatePage('/now', [
            'title' => 'Now test',
            'as_of' => '2026-03-14 00:00',
            'subheading' => 'A subheading for the now page.',
            'sections' => [
                ['id' => 'now-test-work', 'heading' => 'Test work', 'content' => 'Writing tests.'],
                ['id' => 'now-test-blank', 'heading' => '', 'content' => 'A section without a heading.'],
                ['id' => 'now-test-play', 'heading' => 'Test play', 'content' => 'Playing music.'],
            ],
        ]);

        $this->get('/now')
            ->assertOk()
            ->assertSeeInOrder(['As of March 2026.', '<h1', 'Now test', 'A subheading for the now page.'], false)
            ->assertSeeInOrder(['<dt', 'Test work', '<dd', 'Writing tests.', '<dt', 'Test play', '<dd', 'Playing music.'], false)
            ->assertDontSee('A section without a heading.')
            ->assertSee('last tinkered with');
    }
}
