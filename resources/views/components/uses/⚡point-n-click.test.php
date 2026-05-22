<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('uses.point-n-click')
        ->assertStatus(200);
});
