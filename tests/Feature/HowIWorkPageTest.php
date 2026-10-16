<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HowIWorkPageTest extends TestCase
{
    #[Test]
    public function the_how_i_work_page_shows_the_summary_and_each_section(): void
    {
        $this->get('/how-i-work')
            ->assertOk()
            ->assertSeeInOrder(['As of October 2026. People change, and so will this page.', '<h1', 'How I work'], false)
            ->assertSeeInOrder(['The 20-second version', 'if you read nothing else'])
            ->assertSee('How do I reach you, and how fast will you reply?')
            ->assertSeeInOrder(['What can I ask you about?', 'Laravel', '3D printing'])
            ->assertSeeInOrder(['Please do', "Please don't"], false)
            ->assertSee('About this site');
    }

    #[Test]
    public function the_page_nav_links_to_each_section(): void
    {
        $this->get('/how-i-work')
            ->assertOk()
            ->assertSeeInOrder(['On this page', 'href="#the-20-second-version"', 'href="#reaching-me"', 'href="#do-and-dont"', 'href="#about-this-site"'], false)
            ->assertSee('id="reaching-me"', false)
            ->assertSee('id="about-this-site"', false);
    }

    #[Test]
    public function placeholder_prompts_are_styled_as_notes_until_replaced(): void
    {
        $response = $this->get('/how-i-work')->assertOk();

        $this->assertMatchesRegularExpression('/class="[^"]*italic[^"]*"[^>]*>\s*<p>Prompt: your preferred channel/', $response->getContent());
        $this->assertMatchesRegularExpression('/<p>It.{1,6}s built for every brain/', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/class="[^"]*italic[^"]*"[^>]*>\s*<p>It.{1,6}s built for every brain/', $response->getContent());
    }
}
