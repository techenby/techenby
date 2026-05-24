<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Highlight\Highlighter;
use Throwable;

class BlogCodeHighlighter
{
    /**
     * @var array<string, string>
     */
    private array $languageAliases = [
        'html' => 'xml',
        'js' => 'javascript',
        'sh' => 'shell',
        'yml' => 'yaml',
    ];

    /**
     * @var list<string>
     */
    private array $autodetectLanguages = [
        'php',
        'shell',
        'bash',
        'javascript',
        'json',
        'xml',
        'css',
        'yaml',
        'markdown',
    ];

    public function highlight(string $html): string
    {
        if (! str_contains($html, '<pre')) {
            return $html;
        }

        $document = new DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div id="blog-code-root">' . $html . '</div>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//pre/code') as $codeNode) {
            if (! $codeNode instanceof DOMElement) {
                continue;
            }

            $this->highlightCodeNode($document, $codeNode);
        }

        $root = $document->getElementById('blog-code-root');

        if (! $root instanceof DOMElement) {
            return $html;
        }

        return $this->innerHtml($document, $root);
    }

    private function highlightCodeNode(DOMDocument $document, DOMElement $codeNode): void
    {
        $code = $codeNode->textContent;

        if (trim($code) === '') {
            return;
        }

        try {
            $highlighter = new Highlighter;
            $language = $this->languageFromClass($codeNode->getAttribute('class')) ?? $this->languageFromCode($code);

            $highlighted = $language
                ? $highlighter->highlight($language, $code)
                : $highlighter->highlightAuto($code, $this->autodetectLanguages);
        } catch (Throwable) {
            return;
        }

        while ($codeNode->firstChild) {
            $codeNode->removeChild($codeNode->firstChild);
        }

        $fragment = $document->createDocumentFragment();

        if (! $fragment->appendXML($highlighted->value)) {
            $codeNode->appendChild($document->createTextNode($code));

            return;
        }

        $codeNode->appendChild($fragment);
        $codeNode->setAttribute('class', trim('hljs language-' . $highlighted->language));
    }

    private function languageFromClass(string $class): ?string
    {
        if (! preg_match('/(?:^|\s)(?:language|lang)-([a-z0-9_-]+)/i', $class, $matches)) {
            return null;
        }

        $language = strtolower($matches[1]);

        return $this->languageAliases[$language] ?? $language;
    }

    private function languageFromCode(string $code): ?string
    {
        $trimmedCode = ltrim($code);

        if (preg_match('/^(composer|php artisan|npm|pnpm|yarn|vendor\/bin|git|curl)\b/', $trimmedCode)) {
            return 'shell';
        }

        if (str_contains($trimmedCode, '<?php') || preg_match('/\b(class|function|namespace|protected|public|private|return)\b/', $trimmedCode)) {
            return 'php';
        }

        return null;
    }

    private function innerHtml(DOMDocument $document, DOMElement $element): string
    {
        $html = '';

        foreach ($element->childNodes as $childNode) {
            $html .= $document->saveHTML($childNode);
        }

        return $html;
    }
}
