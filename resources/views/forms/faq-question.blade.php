<form action="{{ route('statamic.forms.submit', 'faq_questions') }}" method="post" class="section">
    @csrf
    <x-atom.subheading>Submit Question?</x-atom.subheading>

    @if (session('form.faq_questions.success'))
        <x-atom.alert>&#9654; SAVED TO ANDY&rsquo;S PC.</x-atom.alert>
    @else
        <label for="faq-question" class="sr-only">Question</label>
        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto] sm:items-stretch">
            <x-atom.input id="faq-question" type="text" name="question" value="{{ old('question') }}" required maxlength="500" placeholder="What should I answer next?" class="min-h-12 w-full border-2 border-neutral-900 bg-white py-3 pr-3 pl-8 font-['Geist_Mono'] text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline focus:outline-2 focus:-outline-offset-1 focus:outline-orange-600 max-sm:text-base/6 dark:border-white/15 dark:bg-white/5 dark:text-neutral-100 dark:placeholder:text-neutral-500 dark:focus:outline-orange-400" />
            <x-atom.button type="submit" variant="primary">Ask It</x-atom.button>
        </div>
        <x-atom.error name="question" />
        <x-atom.error name="form.faq_questions" />
    @endif
</form>
