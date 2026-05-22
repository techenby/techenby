<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('uses.list')
        ->assertStatus(200);
});
