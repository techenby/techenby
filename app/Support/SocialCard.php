<?php

namespace App\Support;

use GdImage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Statamic\Contracts\Entries\Entry;

class SocialCard
{
    /**
     * @var list<string>
     */
    public const COLLECTIONS = ['blog', 'tips'];

    public const WIDTH = 1200;

    public const HEIGHT = 630;

    private const PADDING_LEFT = 132;

    private const TEXT_WIDTH = 960;

    /**
     * Title sizes to try, largest first, until the title fits in four lines.
     *
     * @var list<int>
     */
    private const TITLE_SIZES = [52, 46, 40, 34];

    public function __construct(public BlogSeries $series) {}

    public function supports(Entry $entry): bool
    {
        return in_array($entry->collectionHandle(), self::COLLECTIONS, true);
    }

    /**
     * The card's public URL, drawing it first if it doesn't exist yet.
     */
    public function url(Entry $entry): string
    {
        $path = $this->path($entry);

        if (! $this->disk()->exists($path)) {
            $this->generate($entry);
        }

        return $this->disk()->url($path);
    }

    /**
     * Draw the card into public/social-cards, replacing any older version.
     */
    public function generate(Entry $entry): void
    {
        $this->delete($entry);

        $this->disk()->put($this->path($entry), $this->render($this->label($entry), (string) $entry->get('title')));
    }

    public function delete(Entry $entry): void
    {
        $this->disk()->delete(
            collect($this->disk()->files())->filter(fn (string $file) => str_starts_with($file, $entry->id().'-'))->all()
        );
    }

    /**
     * Named by entry id so slug changes don't orphan files, plus a fingerprint so
     * a new title gets a new URL and link previews stop showing the old one.
     */
    public function path(Entry $entry): string
    {
        return $entry->id().'-'.substr(md5($this->label($entry).'|'.$entry->get('title')), 0, 8).'.png';
    }

    public function label(Entry $entry): string
    {
        $series = $this->series->for($entry);

        if ($series !== null) {
            return $series['title'].(isset($series['part']) ? ' · Part '.$series['part'] : '');
        }

        return Lately::TYPES[$entry->collectionHandle()] ?? 'Writing';
    }

    public function render(string $label, string $title): string
    {
        $image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);

        imagefilledrectangle($image, 0, 0, self::WIDTH, self::HEIGHT, $this->color($image, '#FFFFFF'));

        $this->drawStripe($image);

        imagettftext($image, 20, 0, self::PADDING_LEFT, 132, $this->color($image, '#7A3FB0'), $this->font('AtkinsonHyperlegibleNext-Bold'), $label);

        $this->drawTitle($image, $title);

        $site = 'techenby.com';
        $siteFont = $this->font('AtkinsonHyperlegibleNext-Bold');
        $box = imagettfbbox(22, 0, $siteFont, $site);

        imagettftext($image, 22, 0, self::PADDING_LEFT, 548, $this->color($image, '#27272A'), $siteFont, $site);
        imagefilledrectangle($image, self::PADDING_LEFT, 560, self::PADDING_LEFT + $box[2] - $box[0], 563, $this->color($image, '#9C59D1'));

        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }

    /**
     * The nonbinary flag stripe down the left edge, like the site's.
     */
    private function drawStripe(GdImage $image): void
    {
        foreach (['#FCF434', '#FFFFFF', '#9C59D1', '#2C2C2C'] as $index => $hex) {
            imagefilledrectangle($image, $index * 16, 0, ($index + 1) * 16 - 1, self::HEIGHT, $this->color($image, $hex));
        }
    }

    private function drawTitle(GdImage $image, string $title): void
    {
        $font = $this->font('Recursive-SemiBold');

        foreach (self::TITLE_SIZES as $size) {
            $lines = $this->wrap($title, $font, $size);

            if (count($lines) <= 4) {
                break;
            }
        }

        $lineHeight = (int) round($size * 1.6);
        $color = $this->color($image, '#27272A');

        foreach (array_slice($lines, 0, 4) as $index => $line) {
            imagettftext($image, $size, 0, self::PADDING_LEFT - 3, 220 + $index * $lineHeight, $color, $font, $line);
        }
    }

    /**
     * @return list<string>
     */
    private function wrap(string $text, string $font, int $size): array
    {
        $lines = [];
        $line = '';

        foreach (preg_split('/\s+/', trim($text)) ?: [] as $word) {
            $candidate = $line === '' ? $word : $line.' '.$word;
            $box = imagettfbbox($size, 0, $font, $candidate);

            if ($line !== '' && $box[2] - $box[0] > self::TEXT_WIDTH) {
                $lines[] = $line;
                $line = $word;
            } else {
                $line = $candidate;
            }
        }

        if ($line !== '') {
            $lines[] = $line;
        }

        return $lines;
    }

    private function color(GdImage $image, string $hex): int
    {
        [$red, $green, $blue] = sscanf($hex, '#%02x%02x%02x');

        return imagecolorallocate($image, $red, $green, $blue);
    }

    private function disk(): Filesystem
    {
        return Storage::disk('social_cards');
    }

    private function font(string $name): string
    {
        return resource_path("fonts/{$name}.ttf");
    }
}
