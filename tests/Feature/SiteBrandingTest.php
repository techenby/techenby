<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SiteBrandingTest extends TestCase
{
    #[Test]
    public function pages_link_the_favicon_and_apple_touch_icon(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="icon" href="/favicon.ico" sizes="32x32">', false)
            ->assertSee('<link rel="icon" href="/favicon.svg" type="image/svg+xml">', false)
            ->assertSee('<link rel="apple-touch-icon" href="/apple-touch-icon.png">', false);
    }

    #[Test]
    public function the_linked_icon_files_exist_and_are_copied_into_the_static_build(): void
    {
        foreach (['favicon.ico', 'favicon.svg', 'apple-touch-icon.png'] as $file) {
            $this->assertFileExists(public_path($file));
            $this->assertSame($file, config('statamic.ssg.copy')[public_path($file)] ?? null);
        }
    }

    #[Test]
    public function the_wordmark_links_home_with_an_accessible_name(): void
    {
        $this->get('/log')
            ->assertOk()
            ->assertSee('role="img" aria-label="TechEnby"', false)
            ->assertSee('fill-enby-purple dark:fill-enby-yellow', false);
    }
}
