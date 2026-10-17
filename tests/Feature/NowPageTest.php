<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NowPageTest extends TestCase
{
    #[Test]
    public function the_now_page_shows_when_it_was_updated_and_each_section(): void
    {
        $this->get('/now')
            ->assertOk()
            ->assertSeeInOrder(['As of October 2026.', '<h1', 'Now', 'nownownow.com'], false)
            ->assertSeeInOrder(['<dt', 'Work', '<dd', 'Tighten'], false)
            ->assertSee('Building')
            ->assertSee('last tinkered with');
    }
}
