<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Http;
use Statamic\Events\SubmissionCreated;

class SendFaqQuestionToTelegram
{
    public function handle(SubmissionCreated $event): void
    {
        if ($event->submission->form()->handle() !== 'faq_questions') {
            return;
        }

        Http::timeout(5)
            ->post('https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage', [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => implode("\n\n", [
                    'New FAQ question:',
                    $event->submission->get('question'),
                ]),
            ])
            ->throw();
    }
}
