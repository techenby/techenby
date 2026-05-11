@props(['eyebrow', 'heading', 'subheading'])

<div>
    @if ($eyebrow)
    <x-atom.eyebrow>{{ $eyebrow }}</x-atom.eyebrow>
    @endif

    <x-atom.heading>{{ $heading }}</x-atom.heading>

    @if ($subheading)
        <x-atom.text size="xl">{{ $subheading }}</x-atom.text>
    @endif
</div>
