<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Tests\TestCase;

class FaqPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Entry::query()->where('collection', 'faqs')->get()->each->delete();

        $this->makeFaq('zebra-question', 'Zebra question?', 'The zebra answer.');
        $this->makeFaq('apple-question', 'Apple question?', 'The **apple** answer.');
    }

    #[Test]
    public function the_faq_page_lists_questions_with_their_answers_in_title_order(): void
    {
        $this->updatePage('/faq', ['heading' => 'FAQ test', 'subheading' => 'A subheading for the FAQ.']);

        $this->get('/faq')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'FAQ test', 'A subheading for the FAQ.'], false)
            ->assertSeeInOrder(['Apple question?', 'The <strong>apple</strong> answer.', 'Zebra question?', 'The zebra answer.'], false)
            ->assertSee('id="apple-question"', false)
            ->assertSee('id="zebra-question"', false);
    }

    #[Test]
    public function the_faq_page_points_people_to_social_media_instead_of_a_form(): void
    {
        GlobalSet::findByHandle('andy')->inDefaultSite()->set('comms', [
            ['id' => 'faq-test-social', 'label' => 'Social', 'url' => 'https://social.example.com/@test'],
            ['id' => 'faq-test-email', 'label' => 'Email', 'url' => 'mailto:test@example.com'],
        ])->save();

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Got a question I haven&rsquo;t answered?', false)
            ->assertSee('href="https://social.example.com/@test"', false)
            ->assertDontSee('mailto:', false)
            ->assertDontSee('/!/forms/faq_questions', false)
            ->assertDontSee('<form', false);
    }

    private function makeFaq(string $slug, string $title, string $content): void
    {
        Entry::make()
            ->collection('faqs')
            ->slug($slug)
            ->data(['title' => $title, 'content' => $content])
            ->save();
    }
}
