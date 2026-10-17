<?php

namespace Tests\Feature\Livewire\Uses;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;
use Tests\TestCase;

class ListTest extends TestCase
{
    #[Test]
    public function renders_successfully(): void
    {
        Term::make('list-test-type')->taxonomy('types')->data(['title' => 'List test type'])->save();

        $scene = Entry::make()
            ->collection('uses_scenes')
            ->blueprint('uses_scene')
            ->slug('list-test-scene')
            ->data(['title' => 'List test scene']);

        $scene->save();

        Entry::make()
            ->collection('uses')
            ->blueprint('uses_item')
            ->slug('list-test-item')
            ->data([
                'title' => 'List test item',
                'scene' => $scene->id(),
                'types' => ['list-test-type'],
                'links' => [['id' => 'list-test-link', 'label' => 'List test link', 'url' => 'https://example.com/item']],
            ])
            ->save();

        $this->blade('<x-uses.list />')
            ->assertSeeInOrder(['List test item', 'List test type', 'List test link'])
            ->assertDontSee($scene->id())
            ->assertDontSee(strtoupper($scene->id()));
    }
}
