<?php

use Livewire\Component;

new class extends Component
{

};
?>

@php
    $sceneSlugs = Statamic\Facades\Entry::query()
        ->where('collection', 'uses_scenes')
        ->get()
        ->map(fn ($scene) => $scene->slug());

    $requestedScene = request()->query('scene');
    $activeScene = $sceneSlugs->contains($requestedScene) ? $requestedScene : 'desk';
@endphp

<section class="section uses-shell" data-uses>
    <s:collection:uses_scenes scope="scene">
        <x-dynamic-component :component="$scene->component" :$scene :$activeScene />
    </s:collection:uses_scenes>
</section>
