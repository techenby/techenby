<?php

namespace Tests\Feature;

use App\Support\ArticleOutline;
use App\Support\BlogCodeHighlighter;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;
use Tests\TestCase;

class ArticlePageTest extends TestCase
{
    /**
     * @var list<\Statamic\Contracts\Entries\Entry>
     */
    private array $createdEntries = [];

    protected function tearDown(): void
    {
        foreach ($this->createdEntries as $entry) {
            $entry->delete();
        }

        parent::tearDown();
    }

    #[Test]
    public function a_post_shows_its_header_outline_and_body(): void
    {
        $this->makeEntry('post', 'article-test-post', [
            'title' => 'Article test post',
            'description' => 'A post for the article page.',
            'content' => [
                $this->paragraph('The lede for the article.'),
                $this->heading('First section'),
                $this->paragraph('Some words.'),
                $this->heading('Second section'),
                [
                    'type' => 'set',
                    'attrs' => ['id' => 'gif-set', 'values' => [
                        'type' => 'gif',
                        'url' => 'https://giphy.com/gifs/rmi45iyhIPuRG',
                        'alt' => 'A man pulls his elbow down in triumph.',
                        'caption' => 'me, reading the announcement',
                    ]],
                ],
            ],
        ]);

        $this->get('/log/article-test-post')
            ->assertOk()
            ->assertSee('<meta name="description" content="A post for the article page.">', false)
            ->assertSeeInOrder(['Article test post', 'A post for the article page.', 'min read'])
            ->assertSee('<h2 id="first-section">First section</h2>', false)
            ->assertSee('<h2 id="second-section">Second section</h2>', false)
            ->assertSeeInOrder(['On this page', 'href="#intro"', 'href="#first-section"', 'href="#second-section"'], false)
            ->assertSee('alt="A man pulls his elbow down in triumph."', false)
            ->assertSee('me, reading the announcement')
            ->assertSeeInOrder(['Get Enby&middot;Mail', 'name="fields_first_name"', 'name="email"', 'More from the Log'], false)
            ->assertSee('action="https://track.bentonow.com/forms/f84032af03a501454edca73113402d0d/%24subscribe?hardened=true"', false);
    }

    #[Test]
    public function a_post_in_a_series_shows_the_series_box(): void
    {
        Term::make('article-test-series')
            ->taxonomy('series')
            ->blueprint('series')
            ->data(['title' => 'Article Test Series', 'description' => 'A series for the article page.'])
            ->save();

        $this->makeEntry('post', 'article-test-series-post', [
            'title' => 'Article test series post',
            'series' => 'article-test-series',
            'series_part' => 2,
            'content' => [$this->paragraph('Part two.')],
        ]);

        $this->get('/log/article-test-series-post')
            ->assertOk()
            ->assertSee('Article Test Series · Part 2')
            ->assertSee('A series for the article page.')
            ->assertSeeInOrder(['aria-current="page"', 'Article test series post', 'you are here'], false);
    }

    #[Test]
    public function more_from_the_log_leaves_out_the_current_entry(): void
    {
        $this->makeEntry('post', 'article-test-newest', [
            'title' => 'Article test newest',
            'content' => [$this->paragraph('Newest.')],
        ], date: now()->subMinute());

        $response = $this->get('/log/article-test-newest')->assertOk();

        $this->assertSame(1, substr_count($response->getContent(), 'Article test newest</'));
    }

    #[Test]
    public function a_tip_uses_the_article_layout_without_an_outline(): void
    {
        $this->makeEntry('tip', 'article-test-tip', [
            'title' => 'Article test tip',
            'content' => "Use `afterCreating` sparingly.\n\n```php\nreturn [];\n```",
        ]);

        $this->get('/log/article-test-tip')
            ->assertOk()
            ->assertSeeInOrder(['Tip', 'Article test tip', 'min read'])
            ->assertSee('<code>afterCreating</code>', false)
            ->assertSee('data-language="PHP"', false)
            ->assertDontSee('On this page')
            ->assertDontSee('<meta name="description"', false);
    }

    #[Test]
    public function the_outline_gives_headings_unique_ids(): void
    {
        $outline = new ArticleOutline;

        $html = $outline->anchor('<h2>Intro</h2><h2>Setup &amp; config</h2><h2>Setup &amp; config</h2><h2 id="kept">Kept</h2>');

        $this->assertStringContainsString('<h2 id="intro-2">Intro</h2>', $html);
        $this->assertStringContainsString('<h2 id="setup-config">', $html);
        $this->assertStringContainsString('<h2 id="setup-config-2">', $html);
        $this->assertStringContainsString('<h2 id="kept">Kept</h2>', $html);
        $this->assertSame(['intro-2', 'setup-config', 'setup-config-2', 'kept'], array_column($outline->headings(), 'id'));
        $this->assertSame('Setup & config', $outline->headings()[1]['text']);
    }

    #[Test]
    public function a_post_shows_a_byline_with_the_date_and_reading_time(): void
    {
        $this->makeEntry('post', 'article-test-byline', [
            'title' => 'Article test byline',
            'content' => [$this->paragraph('A short post.')],
        ], date: Carbon::parse('2026-09-12'));

        $this->get('/log/article-test-byline')
            ->assertOk()
            ->assertSeeInOrder(['Article test byline', 'Sep 12, 2026', '1 min read']);
    }

    #[Test]
    public function the_byline_does_not_show_the_author(): void
    {
        $this->makeEntry('post', 'article-test-byline-author', [
            'title' => 'Article test byline author',
            'content' => [$this->paragraph('A short post.')],
        ]);

        $content = $this->get('/log/article-test-byline-author')->assertOk()->getContent();

        $this->assertSame(1, substr_count($content, 'src="/assets/avatar-2025-square.jpeg"'));
        $this->assertSame(1, substr_count($content, '>Andy Swick</'));
    }

    #[Test]
    public function a_code_set_shows_its_language_and_note_in_the_header(): void
    {
        $this->makeEntry('post', 'article-test-code-set', [
            'title' => 'Article test code set',
            'content' => [
                $this->paragraph('Before the code.'),
                [
                    'type' => 'set',
                    'attrs' => ['id' => 'code-set', 'values' => [
                        'type' => 'code',
                        'language' => 'PHP',
                        'note' => 'the one-line fix',
                        'code' => '<?php return $fix;',
                    ]],
                ],
            ],
        ]);

        $this->get('/log/article-test-code-set')
            ->assertOk()
            ->assertSee('data-code-block', false)
            ->assertSeeInOrder(['<figcaption>', 'PHP', 'the one-line fix', '</figcaption>', '<pre'], false)
            ->assertSee('hljs language-php', false)
            ->assertDontSee('<?php return', false);
    }

    #[Test]
    public function highlighted_code_blocks_are_labelled_with_their_language(): void
    {
        $html = app(BlogCodeHighlighter::class)->highlight('<pre><code class="language-js">const a = 1;</code></pre>');

        $this->assertStringContainsString('<pre data-language="JavaScript">', $html);
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
    private function heading(string $text): array
    {
        return ['type' => 'heading', 'attrs' => ['level' => 2], 'content' => [['type' => 'text', 'text' => $text]]];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function makeEntry(string $blueprint, string $slug, array $data, ?Carbon $date = null): void
    {
        $entry = Entry::make()
            ->collection('log')
            ->blueprint($blueprint)
            ->slug($slug)
            ->published(true)
            ->date($date ?? Carbon::parse('2026-01-05'))
            ->data($data);

        $entry->save();

        $this->createdEntries[] = $entry;
    }
}
