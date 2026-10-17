<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Tests\TestCase;

class ProjectsPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Entry::query()->where('collection', 'projects')->get()->each->delete();
    }

    #[Test]
    public function the_projects_page_shows_each_project_with_its_links(): void
    {
        $this->updatePage('/projects', ['heading' => 'Projects test', 'subheading' => 'A subheading for the projects.']);

        $this->makeProject('linked-project', 'Linked project', [
            'content' => 'A project with links.',
            'links' => [
                ['id' => 'projects-test-site', 'label' => 'Website', 'url' => 'https://project.example.com'],
                ['id' => 'projects-test-code', 'label' => 'GitHub', 'url' => 'https://github.com/example/project'],
            ],
        ]);

        $this->makeProject('draft-project', 'Draft project', published: false);

        $this->get('/projects')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Projects test', 'A subheading for the projects.'], false)
            ->assertSeeInOrder(['Linked project', 'A project with links.', 'href="https://project.example.com"', 'Website', 'href="https://github.com/example/project"', 'GitHub'], false)
            ->assertDontSee('Draft project');
    }

    #[Test]
    public function projects_are_listed_in_the_collection_structure_order(): void
    {
        $first = $this->makeProject('zebra-project', 'Zebra project');
        $second = $this->makeProject('apple-project', 'Apple project');
        $third = $this->makeProject('mango-project', 'Mango project');

        Collection::find('projects')->structure()->in('default')->tree([
            ['entry' => $third->id()],
            ['entry' => $first->id()],
            ['entry' => $second->id()],
        ])->save();

        $this->get('/projects')
            ->assertOk()
            ->assertSeeInOrder(['Mango project', 'Zebra project', 'Apple project']);
    }

    #[Test]
    public function a_project_without_a_logo_or_links_still_renders(): void
    {
        $this->makeProject('plain-project', 'Plain project');

        $this->get('/projects')
            ->assertOk()
            ->assertSee('Plain project');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeProject(string $slug, string $title, array $data = [], bool $published = true): EntryContract
    {
        $project = Entry::make()
            ->collection('projects')
            ->blueprint('project')
            ->slug($slug)
            ->published($published)
            ->data(['title' => $title, ...$data]);

        $project->save();

        return $project;
    }
}
