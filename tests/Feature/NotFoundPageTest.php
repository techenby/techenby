<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    #[Test]
    public function a_missing_page_shows_the_not_found_page_with_ways_back(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('Page not found')
            ->assertSee('href="/"', false)
            ->assertSee('href="/log"', false);
    }
}
