<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HowIWorkPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->updatePage('/how-i-work', [
            'title' => 'How I work test',
            'as_of' => '2026-03-14 00:00',
            'subheading' => 'A subheading for the manual.',
            'tldr_heading' => 'Test summary',
            'tldr' => ['The first thing to know.', 'Prompt: something still to write'],
            'sections' => [
                [
                    'id' => 'hiw-test-question',
                    'type' => 'question',
                    'enabled' => true,
                    'heading' => 'A test question?',
                    'toc_label' => 'Test question',
                    'content' => 'A test answer.',
                ],
                [
                    'id' => 'hiw-test-topics',
                    'type' => 'topics',
                    'enabled' => true,
                    'heading' => 'Test topics',
                    'topics' => ['First topic', 'Second topic'],
                ],
                [
                    'id' => 'hiw-test-hidden',
                    'type' => 'question',
                    'enabled' => false,
                    'heading' => 'A hidden question?',
                    'content' => 'A hidden answer.',
                ],
                [
                    'id' => 'hiw-test-do-dont',
                    'type' => 'do_dont',
                    'enabled' => true,
                    'do' => ['A test do'],
                    'dont' => ['A test dont'],
                ],
            ],
        ]);
    }

    #[Test]
    public function the_how_i_work_page_shows_the_summary_and_each_section(): void
    {
        $this->get('/how-i-work')
            ->assertOk()
            ->assertSeeInOrder(['As of March 2026. People change, and so will this page.', '<h1', 'How I work test', 'A subheading for the manual.'], false)
            ->assertSeeInOrder(['Test summary', 'if you read nothing else', 'The first thing to know.', 'Prompt: something still to write'])
            ->assertSeeInOrder(['A test question?', 'A test answer.'])
            ->assertSeeInOrder(['Test topics', 'First topic', 'Second topic'])
            ->assertSeeInOrder(['Please do', 'A test do', "Please don't", 'A test dont'], false)
            ->assertDontSee('A hidden question?');
    }

    #[Test]
    public function the_page_nav_links_to_each_section(): void
    {
        $this->get('/how-i-work')
            ->assertOk()
            ->assertSeeInOrder(['On this page', 'href="#test-summary"', 'href="#test-question"', 'href="#test-topics"', 'href="#do-and-dont"'], false)
            ->assertSee('id="test-question"', false)
            ->assertSee('id="do-and-dont"', false);
    }

    #[Test]
    public function the_summary_heading_falls_back_when_left_blank(): void
    {
        $this->updatePage('/how-i-work', ['tldr_heading' => null]);

        $this->get('/how-i-work')
            ->assertOk()
            ->assertSee('id="the-20-second-version"', false)
            ->assertSeeInOrder(['href="#the-20-second-version"', 'The 20-second version'], false);
    }
}
