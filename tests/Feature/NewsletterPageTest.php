<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterPageTest extends TestCase
{
    #[Test]
    public function the_newsletter_page_shows_its_heading_and_the_sign_up_form(): void
    {
        $this->updatePage('/newsletter', ['title' => 'Newsletter test', 'subheading' => 'A subheading for the newsletter.']);

        $this->get('/newsletter')
            ->assertOk()
            ->assertSee('<meta name="description" content="A subheading for the newsletter.">', false)
            ->assertSeeInOrder(['<h1', 'Newsletter test', 'A subheading for the newsletter.'], false)
            ->assertSeeInOrder(['<form', 'Sign up', 'name="fields_first_name"', 'name="email"', 'Subscribe', '</form>'], false)
            ->assertSee('action="https://track.bentonow.com/forms/', false)
            ->assertDontSee('Get Enby·Mail')
            ->assertDontSee('Monthly-ish updates:')
            ->assertSee('What lands in your inbox');
    }

    #[Test]
    public function the_newsletter_page_offers_the_log_feed_instead(): void
    {
        $this->get('/newsletter')
            ->assertOk()
            ->assertSee('href="' . route('log.feed') . '"', false);
    }

    #[Test]
    public function the_newsletter_page_is_not_in_the_main_navigation(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('href="/newsletter"', false);
    }
}
