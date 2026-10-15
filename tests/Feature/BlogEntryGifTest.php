<?php

namespace Tests\Feature;

use App\Support\BlogContent;
use App\Support\GiphyUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BlogEntryGifTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function giphyLinks(): array
    {
        return [
            'page' => ['https://giphy.com/gifs/yes-score-rmi45iyhIPuRG'],
            'page without slug' => ['https://giphy.com/gifs/rmi45iyhIPuRG'],
            'embed' => ['https://giphy.com/embed/rmi45iyhIPuRG'],
            'media' => ['https://media.giphy.com/media/rmi45iyhIPuRG/giphy.gif'],
            'short media' => ['https://i.giphy.com/rmi45iyhIPuRG.gif'],
        ];
    }

    #[Test]
    #[DataProvider('giphyLinks')]
    public function giphy_links_resolve_to_the_media_url(string $url): void
    {
        $this->assertSame('https://media.giphy.com/media/rmi45iyhIPuRG/giphy.gif', GiphyUrl::toMediaUrl($url));
    }

    #[Test]
    public function non_giphy_links_do_not_resolve(): void
    {
        $this->assertNull(GiphyUrl::toMediaUrl('https://example.com/gifs/yes-score-rmi45iyhIPuRG'));
        $this->assertNull(GiphyUrl::toMediaUrl('https://giphy.com/'));
        $this->assertNull(GiphyUrl::toMediaUrl('not a url'));
    }

    #[Test]
    public function bard_content_is_split_into_html_and_sets_in_order(): void
    {
        $blocks = app(BlogContent::class)->blocks([
            $this->paragraph('Before the gif'),
            $this->gifSet('https://giphy.com/gifs/yes-score-rmi45iyhIPuRG', 'Someone cheering'),
            $this->paragraph('After the gif'),
        ]);

        $this->assertCount(3, $blocks);
        $this->assertSame('html', $blocks[0]['type']);
        $this->assertStringContainsString('Before the gif', $blocks[0]['html']);
        $this->assertSame('set', $blocks[1]['type']);
        $this->assertSame('gif', $blocks[1]['set']);
        $this->assertSame('Someone cheering', $blocks[1]['values']['alt']);
        $this->assertStringContainsString('After the gif', $blocks[2]['html']);
    }

    #[Test]
    public function empty_paragraphs_around_a_set_are_dropped(): void
    {
        $blocks = app(BlogContent::class)->blocks([
            ['type' => 'paragraph'],
            $this->gifSet('https://giphy.com/gifs/yes-score-rmi45iyhIPuRG', 'Someone cheering'),
            ['type' => 'paragraph'],
        ]);

        $this->assertCount(1, $blocks);
        $this->assertSame('set', $blocks[0]['type']);
    }

    #[Test]
    public function gif_set_renders_the_media_url_alt_text_and_attribution(): void
    {
        $html = view('blog.sets.gif', [
            'url' => 'https://giphy.com/embed/rmi45iyhIPuRG',
            'alt' => 'Someone cheering',
        ])->render();

        $this->assertStringContainsString('src="https://media.giphy.com/media/rmi45iyhIPuRG/giphy.gif"', $html);
        $this->assertStringContainsString('alt="Someone cheering"', $html);
        $this->assertStringContainsString('href="https://giphy.com/gifs/rmi45iyhIPuRG"', $html);
        $this->assertStringContainsString('via GIPHY', $html);
    }

    #[Test]
    public function gif_set_renders_nothing_for_a_non_giphy_link(): void
    {
        $html = view('blog.sets.gif', [
            'url' => 'https://example.com/cat.gif',
            'alt' => 'A cat',
        ])->render();

        $this->assertSame('', trim($html));
    }

    /**
     * @return array<string, mixed>
     */
    private function paragraph(string $text): array
    {
        return ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => $text]]];
    }

    /**
     * @return array<string, mixed>
     */
    private function gifSet(string $url, string $alt): array
    {
        return [
            'type' => 'set',
            'attrs' => [
                'id' => 'test-gif',
                'values' => ['type' => 'gif', 'url' => $url, 'alt' => $alt],
            ],
        ];
    }
}
