<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Tests\TestCase;

class SiteFooterTest extends TestCase
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

        Entry::make()
            ->collection('log')
            ->blueprint('make')
            ->slug('a-published-make')
            ->published(true)
            ->date('2026-01-05')
            ->data(['title' => 'A published make'])
            ->save();

        GlobalSet::findByHandle('andy')->inDefaultSite()->set('comms', [
            ['id' => 'footer-test-code', 'label' => 'Code', 'url' => 'https://code.example.com/test'],
            ['id' => 'footer-test-social', 'label' => 'Social', 'url' => 'https://social.example.com/@test'],
            ['id' => 'footer-test-email', 'label' => 'Email', 'url' => 'mailto:test@example.com'],
        ])->save();
    }

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
            'post' => ['/log/a-published-post'],
            'tip' => ['/log/a-published-tip'],
            'make' => ['/log/a-published-make'],
        ];
    }

    #[Test]
    #[DataProvider('pages')]
    public function every_page_has_one_footer_with_the_comms_links(string $uri): void
    {
        $response = $this->get($uri)
            ->assertOk()
            ->assertSeeInOrder(['href="https://code.example.com/test"', 'href="https://social.example.com/@test"'], false)
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
        $this->get('/now')->assertOk()->assertSee('href="mailto:test@example.com"', false);

        $this->get('/faq')->assertOk()->assertDontSee('mailto:', false);
    }

    #[Test]
    public function pages_with_an_entry_show_when_they_were_last_updated(): void
    {
        $this->get('/log/a-published-tip')
            ->assertOk()
            ->assertSee('last tinkered with', false);
    }
}
