<?php

namespace Tests\Feature\Livewire\Uses;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PointNClickTest extends TestCase
{
    #[Test]
    public function renders_successfully(): void
    {
        $this->blade('<x-uses.point-n-click />')
            ->assertSee('data-uses', false);
    }
}
