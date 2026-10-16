<?php

namespace App\Support;

use Illuminate\Support\Str;

class ArticleOutline
{
    /**
     * @var list<array{id: string, text: string}>
     */
    private array $headings = [];

    /**
     * Ids already on the page, so headings never collide with them.
     *
     * @var array<string, int>
     */
    private array $usedIds = ['intro' => 1];

    /**
     * Give every h2 in the HTML an id and remember it for the "On this page" list.
     */
    public function anchor(string $html): string
    {
        return preg_replace_callback('/<h2(?<attributes>[^>]*)>(?<text>.*?)<\/h2>/is', function (array $matches): string {
            $text = trim(html_entity_decode(strip_tags($matches['text']), ENT_QUOTES | ENT_HTML5));

            if (preg_match('/\sid=["\']([^"\']+)["\']/i', $matches['attributes'], $existing) === 1) {
                $this->headings[] = ['id' => $existing[1], 'text' => $text];

                return $matches[0];
            }

            $id = $this->uniqueId(Str::slug($text) ?: 'section');

            $this->headings[] = ['id' => $id, 'text' => $text];

            return '<h2 id="'.e($id).'"'.$matches['attributes'].'>'.$matches['text'].'</h2>';
        }, $html) ?? $html;
    }

    /**
     * @return list<array{id: string, text: string}>
     */
    public function headings(): array
    {
        return $this->headings;
    }

    private function uniqueId(string $id): string
    {
        $count = $this->usedIds[$id] ?? 0;

        $this->usedIds[$id] = $count + 1;

        return $count === 0 ? $id : $id.'-'.($count + 1);
    }
}
