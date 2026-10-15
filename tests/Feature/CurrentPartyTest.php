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
        $members = $this->currentParty();

        $this->assertNotEmpty($members);
        $this->assertLessThanOrEqual(6, count($members));

        $response = $this->get('/')
            ->assertOk()
            ->assertSee('id="current-party-heading"', false)
            ->assertSee(count($members).'/6');

        foreach ($members as $member) {
            $response->assertSee($member['label']);

            if (filled($member['url'] ?? null)) {
                $response->assertSee('href="'.e($member['url']).'"', false);
            }

            if (filled($member['note'] ?? null)) {
                $response->assertSee($member['note']);
            }
        }
    }

    /**
     * @return list<array{label: string, url?: string, note?: string}>
     */
    private function currentParty(): array
    {
        return GlobalSet::findByHandle('andy')->inDefaultSite()->get('current_party') ?? [];
    }
}
