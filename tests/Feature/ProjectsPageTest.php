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
            ->assertSeeInOrder(['<h1', 'Projects', 'A collection of apps, packages, and podcasts'], false)
            ->assertSeeInOrder(['Sunny Home', 'Your household, organized.', 'href="https://sunnyhome.app"', 'Website', 'href="https://github.com/techenby/sunny"', 'GitHub'], false)
            ->assertDontSee('Project Dex');
    }

    #[Test]
    public function projects_are_listed_in_the_collection_structure_order(): void
    {
        $this->get('/projects')
            ->assertOk()
            ->assertSeeInOrder(['Sunny Home', 'Make Ship Happen Podcast', 'Heartsong', 'Card Party', 'Uke or Puke']);
    }

    #[Test]
    public function a_project_without_a_logo_or_links_still_renders(): void
    {
        $this->get('/projects')
            ->assertOk()
            ->assertSee('Uke or Puke');
    }
}
