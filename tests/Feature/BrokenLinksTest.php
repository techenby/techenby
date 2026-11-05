<?php

namespace Tests\Feature;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;
use PHPUnit\Framework\Attributes\Test;
use Statamic\StaticSite\Generator;
use Tests\TestCase;

/**
 * Production only serves the static build, and unknown URLs fall back to the
 * homepage instead of a 404, so a broken link fails silently. This renders
 * every page the generator will build and checks each internal link against
 * what the build will actually contain, not just what Laravel can answer.
 */
class BrokenLinksTest extends TestCase
{
    /**
     * Links, images, scripts, and link preview images worth checking.
     *
     * @var array<string, string>
     */
    private const LINK_ATTRIBUTES = [
        '//a[@href]' => 'href',
        '//link[@href]' => 'href',
        '//img[@src]' => 'src',
        '//script[@src]' => 'src',
        '//meta[@property="og:image"]' => 'content',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('social_cards', ['url' => config('filesystems.disks.social_cards.url')]);

        $this->withoutVite();
    }

    #[Test]
    public function every_internal_link_points_at_something_the_static_build_contains(): void
    {
        $pages = $this->staticPages();
        $broken = [];

        foreach ($pages as $page) {
            $response = $this->get($page);

            if ($response->getStatusCode() !== 200 && $page !== '/404') {
                $broken[] = "{$page} itself returns {$response->getStatusCode()}";

                continue;
            }

            foreach ($this->internalLinks((string) $response->getContent(), $page) as $link) {
                if (! $this->isInStaticBuild($link, $pages)) {
                    $broken[] = "{$page} → {$link}";
                }
            }
        }

        $this->assertSame([], array_values(array_unique($broken)), "Broken links:\n" . implode("\n", array_unique($broken)));
    }

    /**
     * Every page path the static site generator will write, from the same list it builds from.
     *
     * @return list<string>
     */
    private function staticPages(): array
    {
        $generator = app(Generator::class);

        return (fn () => $this->gatherAllPages())
            ->call($generator)
            ->map(fn ($page) => $this->normalize((string) $page->url()))
            ->push('/404')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Internal URLs on a page, as paths without query strings or fragments.
     * Feeds and OPML files are XML, so their links are read from <link> and href attributes.
     *
     * @return list<string>
     */
    private function internalLinks(string $content, string $page): array
    {
        $urls = [];

        if (Str::endsWith($page, ['.xml', '.opml'])) {
            preg_match_all('/<link>([^<]+)<\/link>|href="([^"]+)"/', $content, $matches);
            $urls = array_filter(array_merge($matches[1], $matches[2]));
        } else {
            $document = new DOMDocument;
            @$document->loadHTML($content);
            $xpath = new DOMXPath($document);

            foreach (self::LINK_ATTRIBUTES as $query => $attribute) {
                foreach ($xpath->query($query) as $element) {
                    $urls[] = $element->getAttribute($attribute);
                }
            }
        }

        return collect($urls)
            ->map(fn (string $url) => $this->internalPath(html_entity_decode(trim($url))))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * The path of a link to this site, or null for external, mailto, tel, and anchor-only links.
     */
    private function internalPath(string $url): ?string
    {
        if ($url === '' || Str::startsWith($url, ['#', 'mailto:', 'tel:', 'javascript:', 'data:', '//'])) {
            return null;
        }

        $parts = parse_url($url);

        if (isset($parts['host']) && $parts['host'] !== parse_url((string) config('app.url'), PHP_URL_HOST)) {
            return null;
        }

        if (! isset($parts['host']) && ! Str::startsWith($url, '/')) {
            return null;
        }

        return $this->normalize($parts['path'] ?? '/');
    }

    /**
     * Pages the generator writes, files in public/ (which the build copies), social cards,
     * Glide images (generated as pages render), and the Flux and Livewire scripts copied after the build.
     *
     * @param  list<string>  $pages
     */
    private function isInStaticBuild(string $path, array $pages): bool
    {
        if (in_array($path, $pages, true)) {
            return true;
        }

        if (Str::startsWith($path, '/social-cards/')) {
            return Storage::disk('social_cards')->exists(Str::after($path, '/social-cards/'));
        }

        if (Str::startsWith($path, '/' . config('statamic.assets.image_manipulation.route') . '/')) {
            return true;
        }

        $copiedScripts = ['/flux/flux.js', '/flux/flux.min.js', EndpointResolver::scriptPath(true), EndpointResolver::scriptPath(false)];

        if (in_array($path, $copiedScripts, true)) {
            return true;
        }

        return $path !== '/' && is_file(public_path(ltrim($path, '/')));
    }

    private function normalize(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
