<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WritingIndexPagesTest extends TestCase
{
    #[Test]
    public function the_blog_page_lists_posts_as_rows(): void
    {
        $this->get('/blog')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Blog', 'Notes on code, tools, and the context around them.'], false)
            ->assertSee('Building Sunny&#039;s mobile app, mostly by prompting', false)
            ->assertSee('Sunny Goes Native · Part 1')
            ->assertDontSee('Blog Dex');
    }

    #[Test]
    public function the_tips_page_lists_tips_as_rows(): void
    {
        $this->get('/tips')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Tips &amp; Tricks', 'Small, sharp notes on code, tools, and test speed.'], false)
            ->assertSee('Keep Factories Simple', false)
            ->assertDontSee('Tips Dex');
    }
}
