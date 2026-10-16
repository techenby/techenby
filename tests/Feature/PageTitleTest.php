<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PageTitleTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function pages(): array
    {
        return [
            'home' => ['/', 'Andy Swick'],
            'log' => ['/log', 'Log'],
            'log filter' => ['/log/writing', 'Writing · Log'],
            'faq' => ['/faq', 'FAQ'],
            'projects' => ['/projects', 'Projects'],
            'uses' => ['/uses', 'Uses'],
            'blog post' => ['/log/signing-in-from-a-phone-2fa-tokens-and-a-race-condition', 'Signing in from a phone (2FA, tokens, and a race condition)'],
            'tip' => ['/log/keep-factories-simple', 'Keep Factories Simple'],
        ];
    }

    #[Test]
    #[DataProvider('pages')]
    public function each_page_has_its_own_browser_title(string $uri, string $title): void
    {
        config(['app.name' => 'TechEnby']);

        $this->get($uri)
            ->assertOk()
            ->assertSee("<title>{$title} · TechEnby</title>", false);
    }

    #[Test]
    public function the_not_found_page_has_its_own_browser_title(): void
    {
        config(['app.name' => 'TechEnby']);

        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('<title>Page not found · TechEnby</title>', false);
    }
}
