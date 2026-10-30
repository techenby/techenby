@props(['heading' => 'Get Enby·Mail', 'description' => 'Monthly-ish updates: notes on Laravel, prints in progress, the occasional missing 1×2 plate.'])

{{-- Bento FormKit form: posts straight to Bento, which redirects back here with ?bento_form=rdlmvzzr on success. --}}
<flux:card {{ $attributes }}>
    <form
        action="https://track.bentonow.com/forms/f84032af03a501454edca73113402d0d/%24subscribe?hardened=true"
        method="POST"
        enctype="multipart/form-data"
        class="bento-formkit space-y-5"
        id="bento-formkit-rdlmvzzr"
        aria-labelledby="bento-formkit-rdlmvzzr-title"
    >
        <div class="space-y-1.5">
            <flux:heading level="2" id="bento-formkit-rdlmvzzr-title" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">{{ $heading }}</flux:heading>
            @if (filled($description))
                <flux:text class="text-pretty">{{ $description }}</flux:text>
            @endif
        </div>

        <input type="hidden" name="redirect" value="https://techenby.com" />

        <div class="grid gap-4 sm:grid-cols-2">
            <flux:input id="bento-formkit-rdlmvzzr-field-first_name" name="fields_first_name" label="First name" placeholder="Tony Tony" autocomplete="given-name" />
            <flux:input id="bento-formkit-rdlmvzzr-field-email" type="email" name="email" label="Email" badge="Required" required placeholder="chopper@strawhat.pirates" autocomplete="email" />
        </div>

        <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
            <flux:text class="text-sm">I only email you about what you signed up for. Unsubscribe any time.</flux:text>
            <flux:button type="submit" variant="primary">Subscribe</flux:button>
        </div>
    </form>

    <div id="bento-formkit-rdlmvzzr-success" role="status" tabindex="-1" class="space-y-1.5 outline-none" hidden>
        <flux:heading level="2" class="font-display text-xl font-semibold tracking-tight text-zinc-800 dark:text-white">You&rsquo;re on the list!</flux:heading>
        <flux:text class="text-pretty">Your first Enby&middot;Mail is on its way. Keep an eye on your inbox for a welcome note.</flux:text>
    </div>
</flux:card>

@once
    <script>
        (() => {
            const form = document.getElementById('bento-formkit-rdlmvzzr');
            const success = document.getElementById('bento-formkit-rdlmvzzr-success');
            const redirect = form?.querySelector('input[name="redirect"]');

            if (! form || ! success || ! redirect) {
                return;
            }

            const hostOf = (value) => {
                try {
                    return new URL(value, window.location.href).host.replace(/^www\./, '');
                } catch {
                    return '';
                }
            };

            {{-- Only send people back to the page they subscribed from when it's on the domain Bento expects. --}}
            if (hostOf(redirect.value) === hostOf(window.location.href)) {
                const back = new URL(window.location.href);
                back.searchParams.set('bento_form', 'rdlmvzzr');
                back.hash = '';
                redirect.value = back.toString();
            }

            if (new URLSearchParams(window.location.search).get('bento_form') === 'rdlmvzzr') {
                form.hidden = true;
                success.hidden = false;
                success.focus();
            }
        })();
    </script>
    <script src="https://app.bentonow.com/modules/form-sniffer.js" async defer></script>
@endonce
