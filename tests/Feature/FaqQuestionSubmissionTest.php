<?php

namespace Tests\Feature;

use Statamic\Facades\Form;
use Statamic\Facades\FormSubmission;
use Tests\TestCase;

class FaqQuestionSubmissionTest extends TestCase
{
    /**
     * @test
     */
    public function faq_questions_form_is_registered_with_statamic(): void
    {
        $form = Form::find('faq_questions');

        $this->assertNotNull($form);
        $this->assertSame('FAQ Questions', $form->title());
        $this->assertTrue($form->store());
        $this->assertSame(['question'], $form->blueprint()->fields()->all()->keys()->all());
    }

    /**
     * @test
     */
    public function faq_page_shows_the_question_submission_form(): void
    {
        $response = $this->get('/faq');

        $response
            ->assertOk()
            ->assertSee('Submit Question?')
            ->assertSee('/!/forms/faq_questions');
    }

    /**
     * @test
     */
    public function question_is_required_to_submit_faq_question(): void
    {
        $response = $this
            ->from('/faq')
            ->post(route('statamic.forms.submit', 'faq_questions'), [
                'question' => '',
            ]);

        $response
            ->assertRedirect('/faq')
            ->assertSessionHasErrors('question', null, 'form.faq_questions');
    }

    /**
     * @test
     */
    public function visitor_can_submit_a_faq_question(): void
    {
        $existingSubmissionIds = FormSubmission::whereForm('faq_questions')
            ->map
            ->id()
            ->all();

        $response = $this
            ->from('/faq')
            ->post(route('statamic.forms.submit', 'faq_questions'), [
                'question' => 'What keyboard do you use?',
            ]);

        $response
            ->assertRedirect('/faq')
            ->assertSessionHas('form.faq_questions.success');

        $createdSubmissions = FormSubmission::whereForm('faq_questions')
            ->reject(fn ($submission) => in_array($submission->id(), $existingSubmissionIds, true));

        $this->assertCount(1, $createdSubmissions);
        $this->assertSame('What keyboard do you use?', $createdSubmissions->first()->get('question'));

        $createdSubmissions->each->delete();
    }
}
