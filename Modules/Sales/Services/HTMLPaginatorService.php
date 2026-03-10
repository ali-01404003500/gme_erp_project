<?php

namespace Modules\Sales\Services;

use DOMDocument;

class HTMLPaginatorService
{
    private $pageHeight;
    private $marginTop;
    private $marginBottom;
    private $lineHeight;
    private $charsPerLine;

    public function __construct($pageHeightPx = 1123, $marginTop = 50, $marginBottom = 50) {
        // A4 at 96DPI is approx 1123px height
        $this->pageHeight = $pageHeightPx;
        $this->marginTop = $marginTop;
        $this->marginBottom = $marginBottom;
        $this->lineHeight = 20; // Estimated px per line
        $this->charsPerLine = 80; // Estimated chars before wrap
    }

    public function paginate($htmlContent) {
        // 1. Setup DOM
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        // Add encoding to prevent UTF-8 issues
        $dom->loadHTML('<?xml encoding="UTF-8">' . $htmlContent);
        libxml_clear_errors();

        // 2. Get Body Children (The actual content)
        $body = $dom->getElementsByTagName('body')->item(0);
        $elements = $body->childNodes;

        $pages = [];
        $currentPageContent = '';
        $currentHeight = $this->marginTop; // Start with top margin

        // 3. Iterate through block elements
        foreach ($elements as $node) {
            if ($node->nodeType !== XML_ELEMENT_NODE) continue;

            $elementHeight = $this->estimateElementHeight($node);
            $spacing = 10; // Margin between elements

            // Check if element fits on current page
            if (($currentHeight + $elementHeight + $spacing) > ($this->pageHeight - $this->marginBottom)) {
                // Save current page
                $pages[] = $this->wrapPage($currentPageContent);
                
                // Reset for new page
                $currentPageContent = '';
                $currentHeight = $this->marginTop;
            }

            // Add element to current page
            $currentPageContent .= $dom->saveHTML($node);
            $currentHeight += $elementHeight + $spacing;
        }

        // 4. Save the last page if it has content
        if (!empty(trim($currentPageContent))) {
            $pages[] = $this->wrapPage($currentPageContent);
        }

        return $pages;
    }

    private function estimateElementHeight($node) {
        $tag = strtolower($node->tagName);
        $height = 0;

        // 1. Images: Use attribute or default
        if ($tag === 'img') {
            $height = $node->getAttribute('height');
            if (empty($height)) $height = 200; // Default estimate
            return (int)$height;
        }

        // 2. Tables: Hard to estimate, assume large or count rows
        if ($tag === 'table') {
            $rows = $node->getElementsByTagName('tr')->length;
            return ($rows * 30) + 20; 
        }

        // 3. Headings
        if (in_array($tag, ['h1', 'h2', 'h3'])) {
            return 40; 
        }

        // 4. Text Blocks (p, div, li, etc.)
        // Calculate text length to estimate lines
        $text = $node->textContent;
        $charCount = mb_strlen($text);
        
        // Estimate lines
        $lines = ceil($charCount / $this->charsPerLine);
        if ($lines == 0) $lines = 1; // Empty block still has height
        
        $height = $lines * $this->lineHeight;
        
        // Add padding estimate
        $height += 20; 

        return $height;
    }

    private function wrapPage($content) {
        // Return HTML structure for a single page
        return '<div class="print-page">' . $content . '</div>';
    }
}
