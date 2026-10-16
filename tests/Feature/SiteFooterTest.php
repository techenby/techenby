<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SiteFooterTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function pages(): array
    {
        return [
            'home' => ['/'],
            'log' => ['/log'],
            'log filter' => ['/log/writing'],
            'projects' => ['/projects'],
            'uses' => ['/uses'],
            'faq' => ['/faq'],
            'now' => ['/now'],
            'how i work' => ['/how-i-work'],
            'friends' => ['/friends'],
            'post' => ['/log/building-sunnys-mobile-app-mostly-by-prompting'],
            'tip' => ['/log/keep-factories-simple'],
            'make' => ['/log/key-hooks'],
        ];
    }

    #[Test]
    #[DataProvider('pages')]
    public function every_page_has_one_footer_with_the_comms_links(string $uri): void
    {
        $response = $this->get($uri)
            ->assertOk()
            ->assertSee('href="https://github.com/techenby"', false)
            ->assertSee('href="https://bsky.app/profile/techenby.com"', false)
            ->assertSee('href="https://dev-null.sh/@techenby/"', false)
            ->assertSee('&copy; '.now()->year.' Andy Swick', false);

        $this->assertSame(1, substr_count($response->getContent(), '<footer'));
    }

    #[Test]
    public function the_not_found_page_has_the_footer_too(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('<footer', false);
    }

    #[Test]
    public function the_footer_lists_email_everywhere_but_the_faq(): void
    {
        $this->get('/now')->assertOk()->assertSee('href="mailto:andy@techenby.com"', false);

        $this->get('/faq')->assertOk()->assertDontSee('mailto:', false);
    }

    #[Test]
    public function pages_with_an_entry_show_when_they_were_last_updated(): void
    {
        $this->get('/log/keep-factories-simple')
            ->assertOk()
            ->assertSee('last tinkered with', false);
    }
}
