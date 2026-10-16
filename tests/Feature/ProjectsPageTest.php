<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectsPageTest extends TestCase
{
    #[Test]
    public function the_projects_page_shows_each_project_with_its_links(): void
    {
        $this->get('/projects')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Projects', 'A collection of apps and packages'], false)
            ->assertSeeInOrder(['Sunny', 'My LifeOS application', 'href="https://sunnyhome.app"', 'Website'], false)
            ->assertDontSee('Project Dex');
    }

    #[Test]
    public function a_project_without_a_logo_or_links_still_renders(): void
    {
        $this->get('/projects')
            ->assertOk()
            ->assertSee('Uke or Puke');
    }
}
