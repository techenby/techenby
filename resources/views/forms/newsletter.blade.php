<form action="{{ route('newsletter.store') }}" method="post" class="section">
    @csrf
    <x-atom.subheading>Subscribe to Enby·Mail?</x-atom.subheading>
    <x-atom.text>Monthly-ish updates: Notes on Laravel, prints in progress, the occasional missing 1&times;2 plate.</x-atom.text>

    @if (session('newsletter_status') === 'subscribed')
        <x-atom.alert>Gotcha! Check your inpox.</x-atom.alert>
    @else
        <label for="newsletter-email" class="sr-only">Email address</label>
        <div class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto] sm:items-stretch">
            <x-atom.input id="newsletter-email" type="email" name="email" required autocomplete="email" value="{{ old('email') }}" placeholder="trainer@route1.com" />
            <x-atom.button type="submit" variant="primary">Catch Me</x-atom.button>
        </div>
        <x-atom.error name="email"/>
    @endif
</form>
