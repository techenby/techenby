<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\GlobalSet;
use Tests\TestCase;

class SyncReadmeTest extends TestCase
{
    protected string $readmePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->readmePath = $this->fakeStacheDirectory . '/README.md';

        File::ensureDirectoryExists($this->fakeStacheDirectory);

        GlobalSet::findByHandle('andy')->inDefaultSite()->set('current_party', [
            ['id' => 'party-test-linked', 'label' => 'Linked member', 'url' => 'https://member.example.com/a (b)', 'note' => 'A *note* about it.'],
            ['id' => 'party-test-plain', 'label' => 'Plain [member]'],
        ])->save();
    }

    #[Test]
    public function it_replaces_only_the_current_party_list(): void
    {
        File::put($this->readmePath, "## Intro\n\n<!-- current-party:start -->\n- Stale member\n<!-- current-party:end -->\n\nOutro\n");

        $this->artisan('readme:sync', ['--path' => $this->readmePath])
            ->expectsOutput('Synced the Current Party list into the README.')
            ->assertSuccessful();

        $this->assertSame(
            "## Intro\n\n<!-- current-party:start -->\n"
            . "- [Linked member](https://member.example.com/a%20%28b%29): A \\*note\\* about it.\n"
            . "- Plain \\[member\\]\n"
            . "<!-- current-party:end -->\n\nOutro\n",
            File::get($this->readmePath),
        );
    }

    #[Test]
    public function it_leaves_a_synced_readme_alone(): void
    {
        File::put($this->readmePath, "<!-- current-party:start -->\n<!-- current-party:end -->\n");

        $this->artisan('readme:sync', ['--path' => $this->readmePath])->assertSuccessful();
        $synced = File::get($this->readmePath);

        $this->artisan('readme:sync', ['--path' => $this->readmePath])
            ->expectsOutput('The README is already in sync.')
            ->assertSuccessful();

        $this->assertSame($synced, File::get($this->readmePath));
    }

    #[Test]
    public function it_fails_without_the_markers(): void
    {
        File::put($this->readmePath, "## Intro\n");

        $this->artisan('readme:sync', ['--path' => $this->readmePath])->assertFailed();

        $this->assertSame("## Intro\n", File::get($this->readmePath));
    }

    #[Test]
    public function it_fails_when_the_readme_is_missing(): void
    {
        $this->artisan('readme:sync', ['--path' => $this->readmePath])->assertFailed();

        $this->assertFileDoesNotExist($this->readmePath);
    }
}
