<?php

namespace Tests\Feature;

use App\Support\GiphyUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Tests\TestCase;

class BlogEntryGifTest extends TestCase
{
    private ?EntryContract $post = null;

    protected function tearDown(): void
    {
        $this->post?->delete();

        parent::tearDown();
    }

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
    public function a_post_renders_its_text_and_sets_in_order(): void
    {
        $this->makePost([
            $this->paragraph('Before the gif'),
            $this->gifSet('https://giphy.com/gifs/yes-score-rmi45iyhIPuRG', 'Someone cheering'),
            $this->paragraph('After the gif'),
        ]);

        $this->get('/log/gif-test-post')
            ->assertOk()
            ->assertSeeInOrder(['<p>Before the gif</p>', 'src="https://media.giphy.com/media/rmi45iyhIPuRG/giphy.gif"', 'alt="Someone cheering"', '<p>After the gif</p>'], false);
    }

    #[Test]
    public function empty_paragraphs_around_a_set_are_dropped(): void
    {
        $this->makePost([
            ['type' => 'paragraph'],
            $this->gifSet('https://giphy.com/gifs/yes-score-rmi45iyhIPuRG', 'Someone cheering'),
            ['type' => 'paragraph'],
        ]);

        $this->get('/log/gif-test-post')
            ->assertOk()
            ->assertSee('alt="Someone cheering"', false)
            ->assertDontSee('<p></p>', false);
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
     * @param  list<array<string, mixed>>  $content
     */
    private function makePost(array $content): void
    {
        $this->post = Entry::make()
            ->collection('log')
            ->blueprint('post')
            ->slug('gif-test-post')
            ->date(now()->subMinute())
            ->data(['title' => 'Gif test post', 'content' => $content]);

        $this->post->save();
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
