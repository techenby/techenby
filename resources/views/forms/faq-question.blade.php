<form action="{{ route('statamic.forms.submit', 'faq_questions') }}" method="post" class="mt-9 border-t-2 border-dashed border-neutral-300 pt-7 dark:border-white/10">
    @csrf
    <p class="font-['Press_Start_2P'] text-[0.6875rem] text-neutral-900 dark:text-neutral-100">SUBMIT QUESTION?</p>

    @if (session('form.faq_questions.success'))
        <div class="mt-4 border-2 border-orange-700 bg-yellow-50 p-4 font-['Press_Start_2P'] text-[0.6875rem] text-orange-700 shadow-[4px_4px_0_0_#171717] dark:border-orange-400/70 dark:bg-orange-400/10 dark:text-orange-400 dark:shadow-none">
            &#9654; SAVED TO ANDY&rsquo;S PC.
        </div>
    @else
        <label for="faq-question" class="sr-only">Question</label>
        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto] sm:items-stretch">
            <div class="relative">
                <span aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-['Press_Start_2P'] text-[0.625rem] text-orange-700 dark:text-orange-400">&#9654;</span>
                <input id="faq-question" type="text" name="question" value="{{ old('question') }}" required maxlength="500" placeholder="What should I answer next?" class="min-h-12 w-full border-2 border-neutral-900 bg-white py-3 pr-3 pl-8 font-['Geist_Mono'] text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline focus:outline-2 focus:-outline-offset-1 focus:outline-orange-600 max-sm:text-base/6 dark:border-white/15 dark:bg-white/5 dark:text-neutral-100 dark:placeholder:text-neutral-500 dark:focus:outline-orange-400">
            </div>
            <button type="submit" class="inline-flex min-h-12 items-center justify-center gap-2 border-2 border-neutral-900 bg-orange-700 px-4 py-3 font-['Press_Start_2P'] text-[0.6875rem] text-white shadow-[4px_4px_0_0_#171717] transition hover:-translate-y-0.5 hover:bg-orange-800 hover:shadow-[6px_6px_0_0_#171717] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500 dark:border-orange-700 dark:shadow-none">
                ASK IT
            </button>
        </div>
        @error('question', 'form.faq_questions')
            <p class="mt-2 font-['Geist_Mono'] text-xs text-red-700 dark:text-red-400">{{ $message }}</p>
        @enderror
    @endif
</form>
