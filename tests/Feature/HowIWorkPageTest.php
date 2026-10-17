<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class HowIWorkPageTest extends TestCase
{
    #[Test]
    public function the_how_i_work_page_shows_the_summary_and_each_section(): void
    {
        $this->get('/how-i-work')
            ->assertOk()
            ->assertSeeInOrder(['As of October 2026. People change, and so will this page.', '<h1', 'How I work'], false)
            ->assertSeeInOrder(['TL;DR', 'if you read nothing else'])
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
            ->assertSeeInOrder(['On this page', 'href="#tldr"', 'href="#reaching-me"', 'href="#do-and-dont"', 'href="#about-this-site"'], false)
            ->assertSee('id="reaching-me"', false)
            ->assertSee('id="about-this-site"', false);
    }

    #[Test]
    public function the_summary_heading_falls_back_when_left_blank(): void
    {
        Entry::query()->where('collection', 'pages')->where('slug', 'how-i-work')->first()
            ->remove('tldr_heading')
            ->save();

        $this->get('/how-i-work')
            ->assertOk()
            ->assertSee('id="the-20-second-version"', false)
            ->assertSeeInOrder(['href="#the-20-second-version"', 'The 20-second version'], false);
    }
}
