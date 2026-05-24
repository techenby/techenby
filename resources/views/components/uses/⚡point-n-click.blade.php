<?php

use Livewire\Component;

new class extends Component
{

};
?>

<section class="section uses-shell" data-uses>
    <s:collection:uses_scenes scope="scene">
        <x-dynamic-component :component="$scene->component" :$scene />
    </s:collection:uses_scenes>
</section>
