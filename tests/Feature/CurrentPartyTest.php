<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\GlobalSet;
use Tests\TestCase;

class CurrentPartyTest extends TestCase
{
    #[Test]
    public function the_homepage_shows_every_current_party_member(): void
    {
        GlobalSet::findByHandle('andy')->inDefaultSite()->set('current_party', [
            ['id' => 'party-test-linked', 'label' => 'Linked member', 'url' => 'https://member.example.com?a=1&b=2', 'note' => 'A note about it.'],
            ['id' => 'party-test-plain', 'label' => 'Plain member'],
        ])->save();

        $this->get('/')
            ->assertOk()
            ->assertSee('id="current-party-heading"', false)
            ->assertSee('2/6')
            ->assertSeeInOrder(['Linked member', 'A note about it.', 'Plain member'])
            ->assertSee('href="https://member.example.com?a=1&amp;b=2"', false);
    }
}
