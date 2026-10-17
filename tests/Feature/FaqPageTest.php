<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FaqPageTest extends TestCase
{
    #[Test]
    public function the_faq_page_lists_questions_with_their_answers(): void
    {
        $this->get('/faq')
            ->assertOk()
            ->assertSeeInOrder(['<h1', 'FAQ', 'What does TechEnby mean?', 'TechEnby is a combination of Technology and Enby.'], false)
            ->assertSee('id="what-does-techenby-mean"', false);
    }

    #[Test]
    public function the_faq_page_points_people_to_social_media_instead_of_a_form(): void
    {
        $this->get('/faq')
            ->assertOk()
            ->assertSee('Got a question I haven&rsquo;t answered?', false)
            ->assertSee('href="https://bsky.app/profile/techenby.com"', false)
            ->assertDontSee('mailto:andy@techenby.com', false)
            ->assertDontSee('/!/forms/faq_questions', false)
            ->assertDontSee('<form', false);
    }
}
