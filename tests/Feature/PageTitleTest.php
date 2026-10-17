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

        Entry::make()
            ->collection('log')
            ->blueprint('tip')
            ->slug('a-published-tip')
            ->published(true)
            ->date('2026-01-05')
            ->data(['title' => 'A published tip'])
            ->save();

        foreach (['/log' => 'Log test', '/faq' => 'FAQ test', '/projects' => 'Projects test', '/uses' => 'Uses test'] as $uri => $title) {
            $this->updatePage($uri, ['title' => $title]);
        }
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function pages(): array
    {
        return [
            'home' => ['/', 'Andy Swick'],
            'log' => ['/log', 'Log test'],
            'log filter' => ['/log/writing', 'Writing · Log test'],
            'faq' => ['/faq', 'FAQ test'],
            'projects' => ['/projects', 'Projects test'],
            'uses' => ['/uses', 'Uses test'],
            'post' => ['/log/a-published-post', 'A published post'],
            'tip' => ['/log/a-published-tip', 'A published tip'],
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
