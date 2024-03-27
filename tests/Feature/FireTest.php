<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FireTest extends TestCase
{
    public static function urls(): array
    {
        return [
            ['url' => '/', 'see' => 'Andy Newhouse'],
            ['url' => '/posts/big-things-get-smaller', 'see' => 'Big Things Get Smaller'],
            ['url' => '/posts/tags', 'see' => 'Tags'],
            ['url' => '/about', 'see' => 'they/them'],
            ['url' => '/uses', 'see' => 'Uses'],
        ];
    }

    #[Test]
    #[DataProvider('urls')]
    public function can_view_page($url, $see): void
    {
        $this->get($url)
            ->assertSee($see)
            ->assertStatus(200);
    }
}
