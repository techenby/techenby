<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MainNavigationTest extends TestCase
{
    #[Test]
    public function the_sidebar_renders_the_main_navigation_in_tree_order(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'href="/"', 'Home',
                'href="/log"', 'Log',
                'href="/projects"', 'Projects',
                'href="/uses"', 'Uses',
                'href="/faq"', 'FAQ',
                'About me',
                'href="/now"', 'Now',
                'href="/how-i-work"', 'How I work',
            ], false);
    }

    #[Test]
    public function the_current_page_is_marked_in_the_navigation(): void
    {
        $this->get('/faq')
            ->assertOk()
            ->assertSee('href="/faq" data-current', false);
    }
}
