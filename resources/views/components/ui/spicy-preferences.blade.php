<flux:card
    variant="outline"
    size="sm"
    :attributes="$attributes->class('flex flex-col gap-4')"
    x-data="{
        textSize: document.documentElement.dataset.textSize ?? 'base',
        reduceMotion: document.documentElement.classList.contains('reduce-motion'),
        remember(key, value) {
            try { localStorage.setItem(key, value) } catch {}
        },
        syncCarousels(reduce) {
            document.querySelectorAll('ui-carousel').forEach((carousel) => carousel.setAttribute('scroll', reduce ? 'instant' : 'smooth'))
        },
        init() {
            this.syncCarousels(this.reduceMotion)

            this.$watch('textSize', (size) => {
                document.documentElement.dataset.textSize = size
                this.remember('techenby:text-size', size)
                this.$dispatch('techenby-preferences', { textSize: size })
            })

            this.$watch('reduceMotion', (reduce) => {
                document.documentElement.classList.toggle('reduce-motion', reduce)
                this.remember('techenby:reduce-motion', reduce)
                this.syncCarousels(reduce)
                this.$dispatch('techenby-preferences', { reduceMotion: reduce })
            })
        },
    }"
    x-on:techenby-preferences.window="Object.assign($data, $event.detail)"
>
    <flux:heading class="font-semibold">Reading comfort</flux:heading>

    <div class="flex items-center justify-between gap-3">
        <flux:label class="font-normal text-zinc-600 dark:text-zinc-300">Text size</flux:label>
        <flux:radio.group variant="segmented" size="sm" x-model="textSize" aria-label="Text size" class="w-auto">
            <flux:radio value="sm" aria-label="Small text"><span class="text-sm">A</span></flux:radio>
            <flux:radio value="base" aria-label="Default text"><span class="text-base">A</span></flux:radio>
            <flux:radio value="lg" aria-label="Large text"><span class="text-lg">A</span></flux:radio>
        </flux:radio.group>
    </div>

    <flux:field variant="inline" class="justify-between">
        <flux:label class="font-normal text-zinc-600 dark:text-zinc-300">Reduce motion</flux:label>
        <flux:switch x-model="reduceMotion" />
    </flux:field>

    <div class="flex flex-col gap-2">
        <flux:label class="font-normal text-zinc-600 dark:text-zinc-300">Appearance</flux:label>
        <flux:radio.group x-data variant="segmented" size="sm" x-model="$flux.appearance" aria-label="Appearance" class="w-full *:flex-1">
            <flux:radio value="light">Light</flux:radio>
            <flux:radio value="dark">Dark</flux:radio>
            <flux:radio value="system">Auto</flux:radio>
        </flux:radio.group>
    </div>
</flux:card>
