<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeProjectsTest extends TestCase
{
    #[Test]
    public function the_homepage_lists_the_picked_projects_in_order_after_lately(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder(['Lately', 'Things I build', 'Sunny Home', 'Your household, organized.', 'Make Ship Happen Podcast', 'Uke or Puke'])
            ->assertSee('/assets/projects/sunny-logo.png', false)
            ->assertSeeInOrder(['Things I build', 'href="/projects"', 'All projects'], false);
    }

    #[Test]
    public function unpublished_picks_stay_off_the_homepage(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('Docify');
    }
}
