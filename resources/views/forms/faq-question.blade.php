<form action="{{ route('statamic.forms.submit', 'faq_questions') }}" method="post" class="section">
    @csrf
    <x-atom.subheading>Submit Question?</x-atom.subheading>

    @if (session('form.faq_questions.success'))
        <x-atom.alert>&#9654; SAVED TO ANDY&rsquo;S PC.</x-atom.alert>
    @else
        <label for="faq-question" class="sr-only">Question</label>
        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto] sm:items-stretch">
            <flux:input id="faq-question" type="text" name="question" value="{{ old('question') }}" required maxlength="500" placeholder="What should I answer next?" class="min-h-12 focus:outline focus:-outline-offset-1" />
            <x-atom.button type="submit" variant="primary">Ask It</x-atom.button>
        </div>
        <x-atom.error name="question" />
        <x-atom.error name="form.faq_questions" />
    @endif
</form>
