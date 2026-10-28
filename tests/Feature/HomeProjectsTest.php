<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Tests\TestCase;

class HomeProjectsTest extends TestCase
{
    #[Test]
    public function the_homepage_lists_the_picked_projects_in_order_after_lately(): void
    {
        $second = $this->makeProject('home-test-second', 'Second pick', 'The second summary.');
        $first = $this->makeProject('home-test-first', 'First pick', 'The **first** summary.');

        $this->updatePage('/', ['projects' => [$first->id(), $second->id()]]);

        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder(['Lately', 'Things I build', 'First pick', 'The first summary.', 'Second pick', 'The second summary.'])
            ->assertSeeInOrder(['Things I build', 'href="/projects"', 'All projects'], false);
    }

    #[Test]
    public function picked_projects_show_their_links(): void
    {
        $project = $this->makeProject('home-test-links', 'Linked pick', links: [
            ['label' => 'GitHub', 'url' => 'https://github.com/techenby/linked-pick'],
            ['label' => 'Website', 'url' => 'https://linked-pick.test'],
            ['label' => 'Missing URL', 'url' => ''],
        ]);

        $this->updatePage('/', ['projects' => [$project->id()]]);

        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder([
                'Linked pick',
                'href="https://github.com/techenby/linked-pick"', 'GitHub',
                'href="https://linked-pick.test"', 'Website',
            ], false)
            ->assertDontSee('Missing URL');
    }

    #[Test]
    public function unpublished_picks_stay_off_the_homepage(): void
    {
        $published = $this->makeProject('home-test-published', 'Published pick');
        $draft = $this->makeProject('home-test-draft', 'Draft pick', published: false);

        $this->updatePage('/', ['projects' => [$published->id(), $draft->id()]]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Published pick')
            ->assertDontSee('Draft pick');
    }

    #[Test]
    public function the_projects_section_is_hidden_without_picks(): void
    {
        $this->updatePage('/', ['projects' => []]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Things I build');
    }

    /**
     * @param  list<array{label: string, url: string}>  $links
     */
    private function makeProject(string $slug, string $title, string $content = '', bool $published = true, array $links = []): EntryContract
    {
        $project = Entry::make()
            ->collection('projects')
            ->blueprint('project')
            ->slug($slug)
            ->published($published)
            ->data(['title' => $title, 'content' => $content, 'links' => $links]);

        $project->save();

        return $project;
    }
}
