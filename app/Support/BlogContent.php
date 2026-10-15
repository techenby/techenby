<?php

namespace App\Support;

use Statamic\Modifiers\Modify;

class BlogContent
{
    public function __construct(public BlogCodeHighlighter $highlighter) {}

    /**
     * Split raw Bard content into HTML chunks and sets, since Statamic's
     * bard_html modifier drops sets entirely.
     *
     * @param  list<array<string, mixed>>  $nodes
     * @return list<array{type: 'html', html: string}|array{type: 'set', set: string, values: array<string, mixed>}>
     */
    public function blocks(array $nodes): array
    {
        $blocks = [];
        $pending = [];

        foreach ($nodes as $node) {
            if (($node['type'] ?? null) !== 'set') {
                $pending[] = $node;

                continue;
            }

            $blocks = $this->flush($blocks, $pending);
            $pending = [];

            $values = $node['attrs']['values'] ?? [];

            $blocks[] = [
                'type' => 'set',
                'set' => (string) ($values['type'] ?? ''),
                'values' => $values,
            ];
        }

        return $this->flush($blocks, $pending);
    }

    /**
     * @param  list<array<string, mixed>>  $blocks
     * @param  list<array<string, mixed>>  $nodes
     * @return list<array<string, mixed>>
     */
    private function flush(array $blocks, array $nodes): array
    {
        if ($nodes === []) {
            return $blocks;
        }

        $html = $this->highlighter->highlight(Modify::value($nodes)->bardHtml()->fetch());

        if (trim(strip_tags($html, '<img><hr><table>')) !== '') {
            $blocks[] = ['type' => 'html', 'html' => $html];
        }

        return $blocks;
    }
}
