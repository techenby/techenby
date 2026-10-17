<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Tests\TestCase;

class DefaultPageTemplateTest extends TestCase
{
    private ?EntryContract $page = null;

    protected function tearDown(): void
    {
        $this->page?->delete();

        parent::tearDown();
    }

    #[Test]
    public function a_new_page_without_a_template_uses_the_default_layout(): void
    {
        $this->makePage([
            'title' => 'Default page test',
            'eyebrow' => 'A small note',
            'heading' => 'A heading for the page',
            'subheading' => 'A line under the heading.',
            'content' => "Some **markdown** content.\n\n## A section",
        ]);

        $this->get('/default-page-test')
            ->assertOk()
            ->assertSee('<title>Default page test', false)
            ->assertSee('<meta name="description" content="A line under the heading.">', false)
            ->assertSeeInOrder(['A small note', '<h1', 'A heading for the page', 'A line under the heading.', '<strong>markdown</strong>', '<h2>A section</h2>'], false)
            ->assertSee('<footer', false);
    }

    #[Test]
    public function the_heading_falls_back_to_the_title(): void
    {
        $this->makePage(['title' => 'Default page test']);

        $this->get('/default-page-test')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'Default page test', '</h1>'], false);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makePage(array $data): void
    {
        $this->page = Entry::make()
            ->collection('pages')
            ->blueprint('page')
            ->slug('default-page-test')
            ->data($data);

        $this->page->save();
    }
}
