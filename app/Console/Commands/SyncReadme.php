<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Facades\GlobalSet;

#[Signature('readme:sync {--path= : The README to update (defaults to the project README.md)}')]
#[Description('Rewrite the Current Party list in the README from the andy global')]
class SyncReadme extends Command
{
    public const START_MARKER = '<!-- current-party:start -->';

    public const END_MARKER = '<!-- current-party:end -->';

    public function handle(): int
    {
        $path = $this->option('path') ?: base_path('README.md');

        if (! File::exists($path)) {
            $this->error("Couldn't find {$path}.");

            return self::FAILURE;
        }

        $readme = File::get($path);
        $start = strpos($readme, self::START_MARKER);
        $end = strpos($readme, self::END_MARKER);

        if ($start === false || $end === false || $end < $start) {
            $this->error('The README needs ' . self::START_MARKER . ' and ' . self::END_MARKER . ' markers around the Current Party list.');

            return self::FAILURE;
        }

        $synced = substr($readme, 0, $start)
            . self::START_MARKER . "\n"
            . $this->currentPartyList()
            . substr($readme, $end);

        if ($synced === $readme) {
            $this->info('The README is already in sync.');

            return self::SUCCESS;
        }

        File::put($path, $synced);

        $this->info('Synced the Current Party list into the README.');

        return self::SUCCESS;
    }

    /**
     * One Markdown list line per party member, each ending in a newline.
     */
    protected function currentPartyList(): string
    {
        $members = GlobalSet::findByHandle('andy')?->inDefaultSite()?->get('current_party') ?? [];

        return collect($members)
            ->map(function (array $member): string {
                $label = $this->escape($member['label'] ?? '');
                $line = filled($member['url'] ?? null) ? "[{$label}](" . $this->escapeUrl($member['url']) . ')' : $label;

                return '- ' . $line . (filled($member['note'] ?? null) ? ': ' . $this->escape($member['note']) : '') . "\n";
            })
            ->implode('');
    }

    /**
     * Keep labels and notes from being read as Markdown formatting.
     */
    protected function escape(string $text): string
    {
        return addcslashes(trim($text), '\\`*_[]<>');
    }

    /**
     * Encode the characters that would end a Markdown link destination early.
     */
    protected function escapeUrl(string $url): string
    {
        return str_replace([' ', '(', ')', '<', '>'], ['%20', '%28', '%29', '%3C', '%3E'], trim($url));
    }
}
