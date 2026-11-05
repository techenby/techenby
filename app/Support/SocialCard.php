<?php

namespace App\Support;

use GdImage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Statamic\Contracts\Assets\Asset;
use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\GlobalSet;

class SocialCard
{
    /**
     * Log blueprints that get a social card.
     *
     * @var list<string>
     */
    public const BLUEPRINTS = ['post', 'tip', 'talk', 'make', 'moment'];

    public const PAGES_COLLECTION = 'pages';

    /**
     * Part of every card's file name, so bumping it redraws every card after a design change.
     */
    public const DESIGN_VERSION = 2;

    public const WIDTH = 1200;

    public const HEIGHT = 630;

    private const PADDING_LEFT = 132;

    private const TEXT_WIDTH = 960;

    /**
     * Title sizes to try, largest first, until the title fits in three lines.
     *
     * @var list<int>
     */
    private const TITLE_SIZES = [52, 46, 40, 34];

    private const TITLE_MAX_LINES = 3;

    /**
     * The home page has no label, so its headline starts higher and gets a fourth line.
     */
    private const HOME_TITLE_MAX_LINES = 4;

    private const TITLE_BASELINE = 220;

    private const HOME_TITLE_BASELINE = 150;

    private const IDENTITY_TOP = 494;

    private const AVATAR_SIZE = 72;

    private const WORDMARK_WIDTH = 224;

    private const WORDMARK_HEIGHT = 48;

    private const RIGHT_EDGE = 1104;

    public function __construct(public BlogSeries $series, public Lately $lately) {}

    /**
     * Every Log entry type except episodes, and every page.
     */
    public function supports(Entry $entry): bool
    {
        return match ($entry->collectionHandle()) {
            Lately::COLLECTION => in_array($entry->blueprint()->handle(), self::BLUEPRINTS, true),
            self::PAGES_COLLECTION => true,
            default => false,
        };
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

        $this->disk()->put($this->path($entry), $this->draw($entry));
    }

    /**
     * The card's PNG for an entry, as it is right now.
     */
    public function draw(Entry $entry): string
    {
        return $this->render($this->label($entry), $this->title($entry), $this->isHome($entry) ? self::HOME_TITLE_MAX_LINES : self::TITLE_MAX_LINES);
    }

    public function delete(Entry $entry): void
    {
        $this->disk()->delete(
            collect($this->disk()->files())->filter(fn (string $file) => str_starts_with($file, $entry->id() . '-'))->all()
        );
    }

    /**
     * Named by entry id so slug changes don't orphan files, plus a fingerprint so
     * a new title gets a new URL and link previews stop showing the old one.
     */
    public function path(Entry $entry): string
    {
        return $entry->id() . '-' . substr(md5(self::DESIGN_VERSION . '|' . $this->label($entry) . '|' . $this->title($entry)), 0, 8) . '.png';
    }

    /**
     * The entry's title, or the home page's headline, since its title is just "Home".
     */
    public function title(Entry $entry): string
    {
        if ($this->isHome($entry)) {
            return (string) ($entry->get('heading') ?: $entry->get('title'));
        }

        return (string) $entry->get('title');
    }

    /**
     * The purple line above the title: the series, a make's kind, or the entry type. Pages have none.
     */
    public function label(Entry $entry): ?string
    {
        if ($entry->collectionHandle() === self::PAGES_COLLECTION) {
            return null;
        }

        $series = $this->series->for($entry);

        if ($series !== null) {
            return $series['title'] . (isset($series['part']) ? ' · Part ' . $series['part'] : '');
        }

        $blueprint = $entry->blueprint()->handle();

        if ($blueprint === 'make') {
            return $this->lately->kind($entry) ?? Lately::TYPES['make'];
        }

        return Lately::TYPES[$blueprint] ?? 'Writing';
    }

    public function render(?string $label, string $title, int $maxLines = self::TITLE_MAX_LINES): string
    {
        $image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);

        imagefilledrectangle($image, 0, 0, self::WIDTH, self::HEIGHT, $this->color($image, '#FFFFFF'));

        $this->drawStripe($image);

        if (filled($label)) {
            imagettftext($image, 20, 0, self::PADDING_LEFT, 132, $this->color($image, '#7A3FB0'), $this->font('AtkinsonHyperlegibleNext-Bold'), $label);
        }

        $this->drawTitle($image, $title, $maxLines);
        $this->drawIdentity($image);

        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }

    /**
     * The largest title size that fits in the allowed lines. A title too long even at
     * the smallest size keeps what fits and ends in an ellipsis rather than losing words silently.
     *
     * @return array{size: int, lines: list<string>}
     */
    public function titleLayout(string $title, int $maxLines = self::TITLE_MAX_LINES): array
    {
        $font = $this->font('Recursive-SemiBold');

        foreach (self::TITLE_SIZES as $size) {
            $lines = $this->wrap($title, $font, $size);

            if (count($lines) <= $maxLines) {
                return ['size' => $size, 'lines' => $lines];
            }
        }

        $lines = array_slice($lines, 0, $maxLines);
        $words = explode(' ', (string) array_pop($lines));

        while (count($words) > 1 && $this->textWidth(implode(' ', $words) . '…', $font, $size) > self::TEXT_WIDTH) {
            array_pop($words);
        }

        $lines[] = rtrim(implode(' ', $words), ' ,;:—-') . '…';

        return ['size' => $size, 'lines' => $lines];
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

    private function drawTitle(GdImage $image, string $title, int $maxLines): void
    {
        ['size' => $size, 'lines' => $lines] = $this->titleLayout($title, $maxLines);

        $font = $this->font('Recursive-SemiBold');
        $lineHeight = (int) round($size * 1.6);
        $color = $this->color($image, '#27272A');
        $baseline = $maxLines > self::TITLE_MAX_LINES ? self::HOME_TITLE_BASELINE : self::TITLE_BASELINE;

        foreach ($lines as $index => $line) {
            imagettftext($image, $size, 0, self::PADDING_LEFT - 3, $baseline + $index * $lineHeight, $color, $font, $line);
        }
    }

    private function textWidth(string $text, string $font, int $size): int
    {
        $box = imagettfbbox($size, 0, $font, $text);

        return $box[2] - $box[0];
    }

    /**
     * Andy's avatar, name, and pronouns on the left, the TechEnby wordmark on the right.
     */
    private function drawIdentity(GdImage $image): void
    {
        $avatar = $this->avatar();
        $nameLeft = self::PADDING_LEFT;

        if ($avatar !== null) {
            imagecopyresampled($image, $avatar, self::PADDING_LEFT, self::IDENTITY_TOP, 0, 0, self::AVATAR_SIZE, self::AVATAR_SIZE, imagesx($avatar), imagesy($avatar));
            $nameLeft += self::AVATAR_SIZE + 20;
        }

        imagettftext($image, 21, 0, $nameLeft, self::IDENTITY_TOP + 31, $this->color($image, '#27272A'), $this->font('Recursive-SemiBold'), 'Andy Swick');
        imagettftext($image, 16.5, 0, $nameLeft, self::IDENTITY_TOP + 61, $this->color($image, '#71717A'), $this->font('AtkinsonHyperlegibleNext-Regular'), 'they/them');

        $wordmark = imagecreatefrompng(resource_path('images/social-card-wordmark.png'));
        $wordmarkTop = self::IDENTITY_TOP + intdiv(self::AVATAR_SIZE - self::WORDMARK_HEIGHT, 2);

        imagecopyresampled($image, $wordmark, self::RIGHT_EDGE - self::WORDMARK_WIDTH, $wordmarkTop, 0, 0, self::WORDMARK_WIDTH, self::WORDMARK_HEIGHT, imagesx($wordmark), imagesy($wordmark));
    }

    /**
     * The avatar from the Andy globals, cropped to a circle. Drawn at four times
     * the size so the edge is smooth once it's scaled down onto the card.
     */
    private function avatar(): ?GdImage
    {
        $asset = GlobalSet::findByHandle('andy')?->inCurrentSite()?->augmentedValue('avatar')->value();
        $photo = $asset instanceof Asset ? @imagecreatefromstring((string) $asset->contents()) : false;

        if ($photo === false) {
            return null;
        }

        $size = self::AVATAR_SIZE * 4;
        $side = min(imagesx($photo), imagesy($photo));
        $avatar = imagecreatetruecolor($size, $size);

        imagealphablending($avatar, false);
        imagesavealpha($avatar, true);
        imagecopyresampled($avatar, $photo, 0, 0, intdiv(imagesx($photo) - $side, 2), intdiv(imagesy($photo) - $side, 2), $size, $size, $side, $side);

        $transparent = imagecolorallocatealpha($avatar, 0, 0, 0, 127);
        $radius = $size / 2;

        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                if (($x + 0.5 - $radius) ** 2 + ($y + 0.5 - $radius) ** 2 > $radius ** 2) {
                    imagesetpixel($avatar, $x, $y, $transparent);
                }
            }
        }

        return $avatar;
    }

    /**
     * @return list<string>
     */
    private function wrap(string $text, string $font, int $size): array
    {
        $lines = [];
        $line = '';

        foreach (preg_split('/\s+/', trim($text)) ?: [] as $word) {
            $candidate = $line === '' ? $word : $line . ' ' . $word;

            if ($line !== '' && $this->textWidth($candidate, $font, $size) > self::TEXT_WIDTH) {
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

    private function isHome(Entry $entry): bool
    {
        return $entry->collectionHandle() === self::PAGES_COLLECTION && $entry->uri() === '/';
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
