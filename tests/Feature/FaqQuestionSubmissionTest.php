<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Form;
use Statamic\Facades\FormSubmission;
use Tests\TestCase;

class FaqQuestionSubmissionTest extends TestCase
{
    #[Test]
    public function faq_questions_form_is_registered_with_statamic(): void
    {
        $form = Form::find('faq_questions');

        $this->assertNotNull($form);
        $this->assertSame('FAQ Questions', $form->title());
        $this->assertTrue($form->store());
        $this->assertSame(['question'], $form->blueprint()->fields()->all()->keys()->all());
    }

    #[Test]
    public function faq_page_shows_the_question_submission_form(): void
    {
        $response = $this->get('/faq');

        $response
            ->assertOk()
            ->assertSee('Submit Question?')
            ->assertSee('/!/forms/faq_questions');
    }

    #[Test]
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

    #[Test]
    public function visitor_can_submit_a_faq_question(): void
    {
        Http::fake();

        $existingSubmissionIds = FormSubmission::whereForm('faq_questions')
            ->map
            ->id()
            ->all();

        $this->from('/faq')
            ->post(route('statamic.forms.submit', 'faq_questions'), [
                'question' => 'What keyboard do you use?',
            ])
            ->assertRedirect('/faq')
            ->assertSessionHas('form.faq_questions.success');

        $createdSubmissions = FormSubmission::whereForm('faq_questions')
            ->reject(fn ($submission) => in_array($submission->id(), $existingSubmissionIds, true));

        $this->assertCount(1, $createdSubmissions);
        $this->assertSame('What keyboard do you use?', $createdSubmissions->first()->get('question'));

        $createdSubmissions->each->delete();
    }

    #[Test]
    public function faq_question_submission_sends_a_telegram_message_when_configured(): void
    {
        config([
            'services.telegram.bot_token' => 'test-bot-token',
            'services.telegram.chat_id' => 'test-chat-id',
        ]);

        Http::fake([
            'api.telegram.org/bottest-bot-token/sendMessage' => Http::response(['ok' => true]),
        ]);

        $existingSubmissionIds = FormSubmission::whereForm('faq_questions')
            ->map
            ->id()
            ->all();

        $response = $this
            ->from('/faq')
            ->post(route('statamic.forms.submit', 'faq_questions'), [
                'question' => 'Can I submit questions from mobile?',
            ])
            ->assertRedirect('/faq')
            ->assertSessionHas('form.faq_questions.success');

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.telegram.org/bottest-bot-token/sendMessage'
                && $request['chat_id'] === 'test-chat-id';
        });

        FormSubmission::whereForm('faq_questions')
            ->reject(fn ($submission) => in_array($submission->id(), $existingSubmissionIds, true))
            ->each
            ->delete();
    }
}
