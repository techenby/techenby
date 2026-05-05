<?php

namespace App\Http\Controllers;

use Bentonow\BentoLaravel\Facades\Bento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc'],
        ]);

        Bento::track($validated['email'], '$completed_onboarding', details: ['source' => 'coming-soon']);

        return back()->with('newsletter_status', 'subscribed');
    }
}
