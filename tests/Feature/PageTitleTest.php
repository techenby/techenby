<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Tests\TestCase;

class PageTitleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Entry::make()
            ->collection('log')
            ->blueprint('post')
            ->slug('a-published-post')
            ->published(true)
            ->date('2026-01-05')
            ->data(['title' => 'A published post'])
            ->save();
    }

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
            'post' => ['/log/a-published-post', 'A published post'],
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
