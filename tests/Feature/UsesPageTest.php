<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UsesPageTest extends TestCase
{
    #[Test]
    public function the_uses_list_filters_in_the_browser_without_livewire(): void
    {
        $response = $this->get('/uses')
            ->assertOk()
            ->assertSee('x-model="search"', false)
            ->assertSee('x-model="types"', false)
            ->assertSee('x-show="matches(', false);

        $this->assertStringNotContainsString('wire:snapshot', $response->getContent());
        $this->assertStringNotContainsString('wire:model', $response->getContent());
    }
}
