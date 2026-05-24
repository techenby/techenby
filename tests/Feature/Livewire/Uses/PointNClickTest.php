<?php

namespace Tests\Feature\Livewire\Uses;

use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PointNClickTest extends TestCase
{
    #[Test]
    public function renders_successfully(): void
    {
        Livewire::test('uses.point-n-click')
            ->assertStatus(200);
    }
}
