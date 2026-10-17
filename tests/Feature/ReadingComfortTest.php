<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReadingComfortTest extends TestCase
{
    #[Test]
    public function it_applies_saved_reading_preferences_before_the_page_paints(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee("localStorage.getItem('techenby:text-size')", false)
            ->assertSee("localStorage.getItem('techenby:reduce-motion')", false)
            ->assertSee("matchMedia('(prefers-reduced-motion: reduce)')", false);
    }

    #[Test]
    public function it_binds_the_reading_comfort_controls(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('x-model="textSize"', false)
            ->assertSee('x-model="reduceMotion"', false)
            ->assertSee('x-model="$flux.appearance"', false)
            ->assertSee('aria-label="Text size"', false)
            ->assertSee('aria-label="Appearance"', false);
    }
}
