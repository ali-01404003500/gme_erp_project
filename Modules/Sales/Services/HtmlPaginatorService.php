<?php

namespace Modules\Sales\Services;

use DOMDocument;
use DOMNode;

class HtmlPaginatorService
{
    protected int $maxHeight;
    protected int $maxWidthPx;
    protected float $avgCharWidth = 8; // px per character (approx)
    protected int $lineHeight = 18;

    public function __construct(int $maxHeight = 800, int $maxWidthPx = 200)
    {
        $this->maxHeight = $maxHeight;
        $this->maxWidthPx = $maxWidthPx;
    }

    /**
     * Calculate characters per line based on container width
     */
    protected function charsPerLine(): int
    {
        return floor($this->maxWidthPx / $this->avgCharWidth);
    }

    /**
     * Entry point
     * @return array{pages: array, remainingHeight: int}
     */
    public function paginate(string $html): array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $body = $dom->getElementsByTagName('body')->item(0);

        $currentHeight = 0;

        $pages = $this->splitNode($body, $currentHeight, $dom);

        $remainingHeight = $this->maxHeight - $currentHeight;

        return [
            'pages' => $pages,
            'remainingHeight' => $remainingHeight
        ];
    }

    /**
     * Recursive DOM splitter (height only)
     */
    protected function splitNode(DOMNode $node, int &$currentHeight, DOMDocument $dom): array
    {
        $pages = [];
        $currentHtml = '';

        foreach ($node->childNodes as $child) {

            if ($this->isEmptyTextNode($child)) continue;

            $childHeight = $this->estimateNodeHeight($child);

            if ($currentHeight + $childHeight <= $this->maxHeight) {
                $currentHtml .= $dom->saveHTML($child);
                $currentHeight += $childHeight;
                continue;
            }

            // TEXT NODE SPLIT (vertically only)
            if ($child->nodeType === XML_TEXT_NODE) {
                [$fit, $rest] = $this->splitTextVertically($child->textContent, $this->maxHeight - $currentHeight);

                $currentHtml .= htmlspecialchars($fit);
                $pages[] = $currentHtml;

                $currentHtml = htmlspecialchars($rest);
                $currentHeight = $this->estimateTextHeight($rest);

                continue;
            }

            // ELEMENT NODE SPLIT RECURSIVELY
            $subHeight = 0;
            $childPages = $this->splitNode($child, $subHeight, $dom);

            foreach ($childPages as $index => $part) {
                $wrapped = $this->wrapNode($child, $part);

                if ($index === 0) {
                    $currentHtml .= $wrapped;
                    $pages[] = $currentHtml;
                } else {
                    $pages[] = $wrapped;
                }
            }

            $currentHtml = '';
            $currentHeight = 0;
        }

        if (!empty($currentHtml)) {
            $pages[] = $currentHtml;
        }

        return $pages;
    }

    /**
     * Estimate node height (vertical only, with wrapping)
     */
    protected function estimateNodeHeight(DOMNode $node): int
    {
        $text = trim($node->textContent);
        $lines = ceil(strlen($text) / $this->charsPerLine());
        $height = $lines * $this->lineHeight;

        switch ($node->nodeName) {
            case 'h1':
            case 'h2':
            case 'h3':
                $height += 25;
                break;
            case 'img':
                $height += 200;
                break;
            case 'ul':
            case 'ol':
                $height += 20;
                break;
            case 'table':
                $height += 100;
                break;
        }

        return max($height, 20);
    }

    protected function estimateTextHeight(string $text): int
    {
        $lines = ceil(strlen($text) / $this->charsPerLine());
        return $lines * $this->lineHeight;
    }

    /**
     * Split text vertically (by height, not width)
     */
    protected function splitTextVertically(string $text, int $remainingHeight): array
    {
        $maxChars = floor(($remainingHeight / $this->lineHeight) * $this->charsPerLine());

        $fit = substr($text, 0, $maxChars);
        $rest = substr($text, $maxChars);

        return [$fit, $rest];
    }

    protected function wrapNode(DOMNode $node, string $innerHtml): string
    {
        if ($node->nodeName === '#text') return $innerHtml;

        $tag = $node->nodeName;
        $attrs = '';

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $attrs .= " {$attr->name}=\"{$attr->value}\"";
            }
        }

        return "<{$tag}{$attrs}>{$innerHtml}</{$tag}>";
    }

    protected function isEmptyTextNode(DOMNode $node): bool
    {
        return $node->nodeType === XML_TEXT_NODE && trim($node->textContent) === '';
    }
}
