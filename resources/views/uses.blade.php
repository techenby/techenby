@php
    use Statamic\Facades\Entry;

    $deskImage = asset('assets/uses/desk-photo-pixelated.jpg');
    $hasDeskImage = file_exists(public_path('assets/uses/desk-photo-pixelated.jpg'));

    $items = Entry::query()
        ->where('collection', 'uses')
        ->get()
        ->sortBy(fn ($entry) => (int) ($entry->value('sort_order') ?? 0))
        ->values();

    $apps = [
        ['name' => 'PhpStorm', 'meta' => 'Code editor', 'color' => 'bg-orange-400'],
        ['name' => 'Terminal', 'meta' => 'Shell + scripts', 'color' => 'bg-neutral-900 dark:bg-neutral-100'],
        ['name' => 'TablePlus', 'meta' => 'Database checks', 'color' => 'bg-yellow-300'],
        ['name' => 'Raycast', 'meta' => 'Launcher', 'color' => 'bg-red-400'],
        ['name' => 'Arc', 'meta' => 'Browser', 'color' => 'bg-sky-400'],
        ['name' => 'Things', 'meta' => 'Tasks', 'color' => 'bg-emerald-400'],
    ];
@endphp

<x-layout :$andy bottomRight="Uses PC · Online">
    <x-fieldset.heading :$eyebrow :$subheading :heading="$heading ?? $title" />

    <section class="section uses-shell" data-uses>
        <div class="uses-toolbar" aria-label="Uses view controls">
            <button type="button" class="uses-tab is-active" data-uses-view-button="desk">Desk</button>
            <button type="button" class="uses-tab" data-uses-view-button="desktop">Desktop</button>
        </div>

        <div data-uses-view="desk">
            <div class="uses-scene-grid">
                <div class="uses-scene" aria-label="Interactive desk setup">
                    @if ($hasDeskImage)
                        <img src="{{ $deskImage }}" alt="Pixelated photo of Andy's desk setup" class="uses-scene-image">
                    @else
                        <div class="uses-scene-placeholder" role="img" aria-label="Desk photo placeholder">
                            <span>DROP PHOTO</span>
                            <strong>public/assets/uses/desk-photo-pixelated.jpg</strong>
                        </div>
                    @endif

                    @foreach ($items as $item)
                        @php
                            $position = collect(['left', 'top', 'width', 'height'])
                                ->map(fn ($field) => $field.': '.$item->value($field).'%;')
                                ->implode(' ');
                        @endphp

                        <button
                            type="button"
                            class="uses-hotspot"
                            style="{{ $position }}"
                            data-uses-item="{{ $item->slug() }}"
                            data-uses-name="{{ $item->value('title') }}"
                            data-uses-type="{{ $item->value('item_type') }}"
                            data-uses-description="{{ $item->value('content') }}"
                            @if ($item->value('action'))
                                data-uses-action="{{ $item->value('action') }}"
                            @endif
                            aria-label="Inspect {{ $item->value('title') }}"
                        >
                            <span></span>
                        </button>
                    @endforeach
                </div>

                <aside class="uses-panel" aria-live="polite" data-uses-panel>
                    <div class="uses-panel-label">Selected Item</div>
                    <h2 data-uses-panel-name>Move cursor</h2>
                    <p class="uses-panel-type" data-uses-panel-type>Hover, focus, or tap an object.</p>
                    <p data-uses-panel-description>Choose the computer to open the desktop view.</p>
                </aside>
            </div>
        </div>

        <div data-uses-view="desktop" hidden>
            <div class="uses-desktop">
                <div class="uses-desktop-bar">
                    <span>ANDY OS</span>
                    <button type="button" data-uses-view-button="desk">Back to desk</button>
                </div>

                <div class="uses-app-grid">
                    @foreach ($apps as $app)
                        <article class="uses-app">
                            <span class="uses-app-icon {{ $app['color'] }}"></span>
                            <h2>{{ $app['name'] }}</h2>
                            <p>{{ $app['meta'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layout>
