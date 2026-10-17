<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Nav;
use Tests\TestCase;

class MainNavigationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Nav::find('main')->in('default')->tree([
            [
                'id' => 'nav-test-home',
                'url' => '/',
                'title' => 'Nav home',
                'children' => [
                    ['id' => 'nav-test-faq', 'url' => '/faq', 'title' => 'Nav FAQ'],
                    ['id' => 'nav-test-log', 'url' => '/log', 'title' => 'Nav log'],
                ],
            ],
            [
                'id' => 'nav-test-group',
                'title' => 'Nav group',
                'children' => [
                    ['id' => 'nav-test-now', 'url' => '/now', 'title' => 'Nav now'],
                ],
            ],
        ])->save();
    }

    #[Test]
    public function the_sidebar_renders_the_main_navigation_in_tree_order(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'href="/"', 'Nav home',
                'href="/faq"', 'Nav FAQ',
                'href="/log"', 'Nav log',
                'Nav group',
                'href="/now"', 'Nav now',
            ], false);
    }

    #[Test]
    public function the_current_page_is_marked_in_the_navigation(): void
    {
        $this->get('/faq')
            ->assertOk()
            ->assertSee('href="/faq" data-current', false)
            ->assertDontSee('href="/log" data-current', false);
    }
}
