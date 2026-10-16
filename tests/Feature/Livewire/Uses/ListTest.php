<?php

namespace Tests\Feature\Livewire\Uses;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListTest extends TestCase
{
    #[Test]
    public function renders_successfully(): void
    {
        $this->blade('<x-uses.list />')
            ->assertSee('Desk')
            ->assertDontSee('e8d0b359-0a07-42c3-8e73-333e03df0436')
            ->assertDontSee('E8D0B359');
    }
}
