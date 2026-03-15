<?php

namespace Modules\Sales\Services;

use DOMDocument;
use DOMNode;
use DOMElement;
use DOMText;
use DOMXPath;

class HTMLPaginatorService
{
    private int $pageHeight;
    private int $marginTop;
    private int $marginBottom;
    private int $usableHeight;

    /**
     * Tag-specific height estimates (px). Override via constructor $config.
     */
    private array $config;

    private static array $defaultConfig = [
        'line_height'          => 20,   // px per line of text
        'chars_per_line'       => 90,   // characters before wrapping
        'paragraph_padding'    => 16,   // top+bottom padding on <p>
        'heading_heights'      => ['h1' => 48, 'h2' => 40, 'h3' => 34, 'h4' => 28, 'h5' => 24, 'h6' => 22],
        'table_row_height'     => 32,   // px per <tr>
        'table_header_height'  => 36,   // px for <thead> row
        'table_border_padding' => 16,   // extra for table borders/padding
        'img_default_height'   => 200,  // when <img> has no height attr
        'li_height'            => 24,   // px per list item
        'hr_height'            => 20,
        'blockquote_padding'   => 24,
        'element_gap'          => 10,   // spacing between sibling elements
        'min_rows_before_break'=> 2,    // don't orphan fewer rows than this
        'widow_lines'          => 2,    // min lines to leave on a page
    ];

    public function __construct(
        int   $pageHeightPx = 1123,
        int   $marginTop    = 50,
        int   $marginBottom = 50,
        array $config       = []
    ) {
        $this->pageHeight    = $pageHeightPx;
        $this->marginTop     = $marginTop;
        $this->marginBottom  = $marginBottom;
        $this->usableHeight  = $pageHeightPx - $marginTop - $marginBottom;
        $this->config        = array_merge(self::$defaultConfig, $config);
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Paginate $htmlContent and return an array of per-page HTML strings.
     * Each string is wrapped in <div class="print-page">…</div>.
     */
    public function paginate(string $htmlContent): array
    {
        $dom  = $this->loadDom($htmlContent);
        $root = $this->resolveRoot($dom);

        // Fix invalid nesting before pagination touches the tree.
        $this->normalizeDom($dom, $root);

        $pages    = [];
        $cursor   = $this->marginTop;
        $pageHtml = '';

        foreach ($root->childNodes as $node) {
            if (!$this->isElement($node)) {
                continue;
            }

            [$fragments, $cursor, $pageHtml] = $this->fitNode(
                $node, $dom, $cursor, $pageHtml, $pages
            );
        }

        // Flush the final page
        if (trim($pageHtml) !== '') {
            $pages[] = $this->wrapPage($pageHtml);
        }

        return $pages;
    }

    /**
     * Return the sentinel wrapper inserted by loadDom().
     * Falls back gracefully to <body> (full documents) or the documentElement.
     */
    private function resolveRoot(DOMDocument $dom): DOMNode
    {
        // 1. Prefer our known sentinel div
        $sentinel = $dom->getElementById('__paginator_root__');
        if ($sentinel !== null) {
            return $sentinel;
        }

        // 2. Full HTML document
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body !== null) {
            return $body;
        }

        // 3. Last resort
        return $dom->documentElement ?? $dom;
    }

    // -------------------------------------------------------------------------
    // DOM normalisation  (runs once before pagination)
    // -------------------------------------------------------------------------

    /**
     * Walk the entire subtree rooted at $root and fix structural problems that
     * would produce invalid HTML when we later split and re-serialise nodes:
     *
     *  1. <p> nested inside <p>  → inner <p> becomes <div>
     *  2. <p> nested inside <li> → inner <p> becomes <span>
     *  3. Block elements (<div>, <p>, …) nested inside <p> → <p> becomes <div>
     *  4. Bare text / inline nodes directly inside <ul>/<ol> → wrap in <li>
     *  5. <li> outside <ul>/<ol>  → wrap in <ul>
     *
     * The method mutates $dom in place and is idempotent.
     */
    private function normalizeDom(DOMDocument $dom, DOMNode $root): void
    {
        $this->normalizeNodeRecursive($dom, $root);
    }

    private function normalizeNodeRecursive(DOMDocument $dom, DOMNode $parent): void
    {
        // Snapshot child list — we may mutate $parent during the loop.
        $children = iterator_to_array($parent->childNodes);

        foreach ($children as $node) {
            if (!$this->isElement($node)) {
                continue;
            }

            $tag = strtolower($node->tagName);

            // ── Rule 1 & 3: block elements inside <p> ──────────────────────
            if ($tag === 'p' && $this->hasBlockChild($node)) {
                $replacement = $this->renameElement($dom, $node, 'div');
                $parent->replaceChild($replacement, $node);
                $this->normalizeNodeRecursive($dom, $replacement);
                continue;
            }

            // ── Rule 2: <p> directly inside <p> ────────────────────────────
            $parentTag = strtolower($parent->tagName ?? '');
            if ($tag === 'p' && $parentTag === 'p') {
                $replacement = $this->renameElement($dom, $node, 'div');
                $parent->replaceChild($replacement, $node);
                $this->normalizeNodeRecursive($dom, $replacement);
                continue;
            }

            // ── Rule 2b: <p> inside <li> → <span> ──────────────────────────
            if ($tag === 'p' && $parentTag === 'li') {
                $replacement = $this->renameElement($dom, $node, 'span');
                $parent->replaceChild($replacement, $node);
                $this->normalizeNodeRecursive($dom, $replacement);
                continue;
            }

            // ── Rule 5: <li> outside a list container ───────────────────────
            if ($tag === 'li' && !in_array($parentTag, ['ul', 'ol'], true)) {
                $ul = $dom->createElement('ul');
                $parent->replaceChild($ul, $node);
                $ul->appendChild($node);
                $this->normalizeNodeRecursive($dom, $ul);
                continue;
            }

            // Recurse into children of this node.
            $this->normalizeNodeRecursive($dom, $node);
        }

        // ── Rule 4 pass: wrap stray inline/text children of <ul>/<ol> ──────
        $parentTag = strtolower($parent->tagName ?? '');
        if (in_array($parentTag, ['ul', 'ol'], true)) {
            $this->wrapOrphanListChildren($dom, $parent);
        }
    }

    /**
     * Returns true if $node contains at least one block-level child element.
     */
    private function hasBlockChild(DOMNode $node): bool
    {
        static $blockTags = [
            'div', 'p', 'ul', 'ol', 'li', 'table', 'thead', 'tbody', 'tfoot',
            'tr', 'th', 'td', 'section', 'article', 'aside', 'header', 'footer',
            'main', 'nav', 'blockquote', 'pre', 'figure', 'figcaption',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'form', 'fieldset',
        ];

        foreach ($node->childNodes as $child) {
            if ($this->isElement($child) && in_array(strtolower($child->tagName), $blockTags, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Create a new element with $newTag, copying all attributes and children
     * from $original, then return it (does NOT insert into the tree).
     */
    private function renameElement(DOMDocument $dom, DOMElement $original, string $newTag): DOMElement
    {
        $new = $dom->createElement($newTag);

        if ($original->hasAttributes()) {
            foreach ($original->attributes as $attr) {
                $new->setAttribute($attr->name, $attr->value);
            }
        }

        foreach (iterator_to_array($original->childNodes) as $child) {
            $new->appendChild($child->cloneNode(true));
        }

        return $new;
    }

    /**
     * Any direct child of a <ul>/<ol> that is NOT an <li> element gets
     * wrapped in a newly created <li> so the list structure stays valid.
     */
    private function wrapOrphanListChildren(DOMDocument $dom, DOMNode $list): void
    {
        $children  = iterator_to_array($list->childNodes);
        $currentLi = null;

        foreach ($children as $child) {
            $isLi = $this->isElement($child) && strtolower($child->tagName) === 'li';

            if ($isLi) {
                $currentLi = null;
                continue;
            }

            if ($currentLi === null) {
                $currentLi = $dom->createElement('li');
                $list->insertBefore($currentLi, $child);
            }

            $currentLi->appendChild($child);
        }
    }

    // -------------------------------------------------------------------------
    // Core recursive fitter
    // -------------------------------------------------------------------------

    /**
     * Try to fit $node onto the current page, splitting it if necessary.
     * Returns [$fragments, $cursor, $pageHtml].
     */
    private function fitNode(
        DOMNode     $node,
        DOMDocument $dom,
        int         $cursor,
        string      $pageHtml,
        array       &$pages
    ): array {
        $tag       = strtolower($node->tagName ?? '');
        $height    = $this->estimateHeight($node);
        $gap       = $this->config['element_gap'];
        $needed    = $height + $gap;
        $remaining = $this->usableHeight - ($cursor - $this->marginTop);

        // ── Fast path: element fits entirely ─────────────────────────────────
        if ($needed <= $remaining || $this->isAtPageTop($cursor)) {
            $pageHtml .= $dom->saveHTML($node);
            $cursor   += $needed;
            return [[], $cursor, $pageHtml];
        }

        // ── Splittable containers: try deep split ─────────────────────────────
        if ($this->isSplittable($tag)) {
            [$cursor, $pageHtml] = $this->splitNode(
                $node, $tag, $dom, $cursor, $pageHtml, $pages, $remaining
            );
            return [[], $cursor, $pageHtml];
        }

        // ── Non-splittable & doesn't fit: push to next page ──────────────────
        $pages[]  = $this->wrapPage($pageHtml);
        $pageHtml = '';
        $cursor   = $this->marginTop;

        $pageHtml .= $dom->saveHTML($node);
        $cursor   += $needed;

        return [[], $cursor, $pageHtml];
    }

    /**
     * Split a container element across pages.
     */
    private function splitNode(
        DOMNode     $node,
        string      $tag,
        DOMDocument $dom,
        int         $cursor,
        string      $pageHtml,
        array       &$pages,
        int         $remaining
    ): array {
        if ($tag === 'table') {
            return $this->splitTable($node, $dom, $cursor, $pageHtml, $pages);
        }

        if (in_array($tag, ['p', 'div', 'section', 'article', 'main', 'blockquote'], true)) {
            return $this->splitBlock($node, $dom, $cursor, $pageHtml, $pages, $remaining);
        }

        if (in_array($tag, ['ul', 'ol'], true)) {
            return $this->splitList($node, $dom, $cursor, $pageHtml, $pages);
        }

        // Fallback: push element to next page
        $pages[]  = $this->wrapPage($pageHtml);
        $pageHtml = $dom->saveHTML($node);
        $cursor   = $this->marginTop + $this->estimateHeight($node) + $this->config['element_gap'];
        return [$cursor, $pageHtml];
    }

    // -------------------------------------------------------------------------
    // Table splitter  (splits between <tr> rows)
    // -------------------------------------------------------------------------

    private function splitTable(
        DOMNode     $table,
        DOMDocument $dom,
        int         $cursor,
        string      $pageHtml,
        array       &$pages
    ): array {
        $gap     = $this->config['element_gap'];
        $minRows = $this->config['min_rows_before_break'];

        $theadHtml = $this->extractTheadHtml($table, $dom);
        $theadH    = $this->config['table_header_height'] + $this->config['table_border_padding'];

        $rows = $this->collectTableRows($table);

        $currentTableRows = [];
        $isFirstTable     = true;

        foreach ($rows as $tr) {
            $rowH      = $this->estimateRowHeight($tr);
            $remaining = $this->usableHeight - ($cursor - $this->marginTop);
            $overhead  = ($theadHtml !== '') ? $theadH : $this->config['table_border_padding'];

            if (($rowH + $overhead) > $remaining && !empty($currentTableRows)) {
                $pageHtml .= $this->buildPartialTable($table, $dom, $theadHtml, $currentTableRows, $isFirstTable);
                $pages[]   = $this->wrapPage($pageHtml);
                $pageHtml  = '';
                $cursor    = $this->marginTop;
                $currentTableRows = [];
                $isFirstTable     = false;
            }

            $currentTableRows[] = $tr;
            $cursor += $rowH;
        }

        // Flush remaining rows
        if (!empty($currentTableRows)) {
            $pageHtml .= $this->buildPartialTable($table, $dom, $theadHtml, $currentTableRows, $isFirstTable);
            $cursor   += $gap;
        }

        return [$cursor, $pageHtml];
    }

    private function extractTheadHtml(DOMNode $table, DOMDocument $dom): string
    {
        foreach ($table->childNodes as $child) {
            if ($this->isElement($child) && strtolower($child->tagName) === 'thead') {
                return $dom->saveHTML($child);
            }
        }
        return '';
    }

    private function collectTableRows(DOMNode $table): array
    {
        $rows = [];
        foreach ($table->childNodes as $child) {
            if (!$this->isElement($child)) continue;
            $childTag = strtolower($child->tagName);
            if ($childTag === 'tr') {
                $rows[] = $child;
            } elseif (in_array($childTag, ['tbody', 'tfoot'], true)) {
                foreach ($child->childNodes as $tr) {
                    if ($this->isElement($tr) && strtolower($tr->tagName) === 'tr') {
                        $rows[] = $tr;
                    }
                }
            }
        }
        return $rows;
    }

    private function buildPartialTable(
        DOMNode     $originalTable,
        DOMDocument $dom,
        string      $theadHtml,
        array       $rows,
        bool        $includeOriginalThead
    ): string {
        $table = $dom->createElement('table');
        if ($originalTable->hasAttributes()) {
            foreach ($originalTable->attributes as $attr) {
                $table->setAttribute($attr->name, $attr->value);
            }
        }

        if ($theadHtml !== '') {
            $theadDom = new DOMDocument('1.0', 'UTF-8');
            libxml_use_internal_errors(true);
            $theadDom->loadHTML(
                '<?xml encoding="UTF-8"><html><body>' . $theadHtml . '</body></html>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );
            libxml_clear_errors();

            $theadNode = $theadDom->getElementsByTagName('thead')->item(0);
            if ($theadNode !== null) {
                $table->appendChild($dom->importNode($theadNode, true));
            }
        }

        $tbody = $dom->createElement('tbody');
        foreach ($rows as $tr) {
            $tbody->appendChild($dom->importNode($tr, true));
        }
        $table->appendChild($tbody);

        return $dom->saveHTML($table);
    }

    private function estimateRowHeight(DOMNode $tr): int
    {
        $h = $this->config['table_row_height'];
        foreach ($tr->childNodes as $cell) {
            if (!$this->isElement($cell)) continue;
            $text  = $cell->textContent;
            $lines = ceil(mb_strlen($text) / ($this->config['chars_per_line'] / 2));
            $h     = max($h, $lines * $this->config['line_height'] + 12);
        }
        return $h;
    }

    // -------------------------------------------------------------------------
    // Block splitter (p / div / section etc.) — splits by child elements
    // -------------------------------------------------------------------------

    private function splitBlock(
        DOMNode     $block,
        DOMDocument $dom,
        int         $cursor,
        string      $pageHtml,
        array       &$pages,
        int         $remaining
    ): array {
        $tag = strtolower($block->tagName);
        $gap = $this->config['element_gap'];

        // Collect element children only
        $children = [];
        foreach ($block->childNodes as $child) {
            if ($this->isElement($child)) {
                $children[] = $child;
            }
        }

        // Leaf node (no element children) — split by line/word count
        if (empty($children)) {
            return $this->splitTextBlock($block, $dom, $cursor, $pageHtml, $pages, $remaining);
        }

        $openTag  = $this->openTag($block);
        $closeTag = '</' . $tag . '>';

        // Open container on current page
        $pageHtml .= $openTag;

        foreach ($children as $child) {
            $pagesBeforeChild = count($pages);

            [$unused, $cursor, $pageHtml] = $this->fitNode(
                $child, $dom, $cursor, $pageHtml, $pages
            );

            // fitNode may have flushed one or MORE pages while inside this
            // container. Every one of those flushed pages is missing its
            // closing tag — append it to each of them now, then reopen the
            // container on the current (not-yet-flushed) page.
            if (count($pages) > $pagesBeforeChild) {
                for ($i = $pagesBeforeChild; $i < count($pages); $i++) {
                    $pages[$i] = $this->wrapPage(
                        $this->unwrapPage($pages[$i]) . $closeTag
                    );
                }
                // Reopen the container for content that follows on the new page
                $pageHtml = $openTag . $pageHtml;
            }
        }

        $pageHtml .= $closeTag;
        $cursor   += $gap;

        return [$cursor, $pageHtml];
    }

    /**
     * Strip the outer <div class="print-page">…</div> wrapper so we can
     * append a closing tag before re-wrapping.
     */
    private function unwrapPage(string $wrappedPage): string
    {
        return preg_replace(
            '/^<div[^>]*class="print-page"[^>]*>(.*)<\/div>$/s',
            '$1',
            $wrappedPage
        );
    }

    /**
     * Split a leaf text block by estimated line count.
     */
    private function splitTextBlock(
        DOMNode     $block,
        DOMDocument $dom,
        int         $cursor,
        string      $pageHtml,
        array       &$pages,
        int         $remaining
    ): array {
        $text         = $block->textContent;
        $tag          = strtolower($block->tagName);
        $lineH        = $this->config['line_height'];
        $charsPerLine = $this->config['chars_per_line'];
        $padding      = $this->config['paragraph_padding'];
        $widowLines   = $this->config['widow_lines'];
        $gap          = $this->config['element_gap'];

        $words      = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $linesAvail = (int) floor(($remaining - $padding) / $lineH);

        if ($linesAvail <= $widowLines || empty($words)) {
            // Not enough room — push entire block to next page
            $pages[]  = $this->wrapPage($pageHtml);
            $pageHtml = '';
            $cursor   = $this->marginTop;
            $pageHtml .= $dom->saveHTML($block);
            $cursor   += $this->estimateHeight($block) + $gap;
            return [$cursor, $pageHtml];
        }

        $charsForFirstPage = $linesAvail * $charsPerLine;
        [$firstWords, $restWords] = $this->splitWordsByChars($words, $charsForFirstPage);

        if (!empty($firstWords)) {
            $pageHtml .= $this->cloneBlockWithText($block, $dom, implode(' ', $firstWords));
            $pages[]   = $this->wrapPage($pageHtml);
            $pageHtml  = '';
            $cursor    = $this->marginTop;
        }

        if (!empty($restWords)) {
            $restBlock  = $this->cloneBlockWithText($block, $dom, implode(' ', $restWords));
            $restHeight = $this->estimateHeightFromText(implode(' ', $restWords), $tag);
            $pageHtml  .= $restBlock;
            $cursor    += $restHeight + $gap;
        }

        return [$cursor, $pageHtml];
    }

    private function splitWordsByChars(array $words, int $maxChars): array
    {
        $first = [];
        $rest  = [];
        $count = 0;
        $split = false;

        foreach ($words as $word) {
            if (!$split && ($count + mb_strlen($word) + 1) <= $maxChars) {
                $first[] = $word;
                $count  += mb_strlen($word) + 1;
            } else {
                $split  = true;
                $rest[] = $word;
            }
        }

        return [$first, $rest];
    }

    /**
     * Clone $block preserving its tag + attributes, but replace its text
     * content with $text. Only called for leaf blocks (no element children).
     */
    private function cloneBlockWithText(DOMNode $block, DOMDocument $dom, string $text): string
    {
        /** @var DOMElement $clone */
        $clone = $block->cloneNode(false); // shallow — attributes only, no children
        $clone->appendChild($dom->createTextNode($text));
        return $dom->saveHTML($clone);
    }

    // -------------------------------------------------------------------------
    // List splitter  (ul / ol → splits between <li> items)
    // -------------------------------------------------------------------------

    private function splitList(
        DOMNode     $list,
        DOMDocument $dom,
        int         $cursor,
        string      $pageHtml,
        array       &$pages
    ): array {
        $tag = strtolower($list->tagName);
        $liH = $this->config['li_height'];
        $gap = $this->config['element_gap'];

        $items = [];
        foreach ($list->childNodes as $child) {
            if ($this->isElement($child) && strtolower($child->tagName) === 'li') {
                $items[] = $child;
            }
        }

        if (empty($items)) {
            return [$cursor, $pageHtml];
        }

        $currentList = $this->createListShell($list, $dom);

        foreach ($items as $li) {
            $itemH     = max($liH, $this->estimateHeight($li));
            $remaining = $this->usableHeight - ($cursor - $this->marginTop);

            $listIsEmpty = !$currentList->hasChildNodes();

            if ($itemH > $remaining && !$listIsEmpty) {
                $pageHtml .= $dom->saveHTML($currentList);
                $pages[]   = $this->wrapPage($pageHtml);
                $pageHtml  = '';
                $cursor    = $this->marginTop;
                $currentList = $this->createListShell($list, $dom);
            }

            $currentList->appendChild($dom->importNode($li, true));
            $cursor += $itemH;
        }

        // Flush remaining items
        if ($currentList->hasChildNodes()) {
            $pageHtml .= $dom->saveHTML($currentList);
            $cursor   += $gap;
        }

        return [$cursor, $pageHtml];
    }

    /**
     * Create a new empty list element (ul/ol) that inherits the original's
     * tag name and attributes, ready to receive imported <li> children.
     */
    private function createListShell(DOMNode $original, DOMDocument $dom): DOMElement
    {
        $tag   = strtolower($original->tagName);
        $shell = $dom->createElement($tag);
        if ($original->hasAttributes()) {
            foreach ($original->attributes as $attr) {
                $shell->setAttribute($attr->name, $attr->value);
            }
        }
        return $shell;
    }

    // -------------------------------------------------------------------------
    // Height estimation
    // -------------------------------------------------------------------------

    private function estimateHeight(DOMNode $node): int
    {
        if (!$this->isElement($node)) return 0;

        $tag = strtolower($node->tagName);

        if ($tag === 'img') {
            $h = (int) $node->getAttribute('height');
            return $h > 0 ? $h : $this->config['img_default_height'];
        }

        if ($tag === 'hr') {
            return $this->config['hr_height'];
        }

        if (isset($this->config['heading_heights'][$tag])) {
            return $this->config['heading_heights'][$tag];
        }

        if ($tag === 'table') {
            return $this->estimateTableHeight($node);
        }

        if (in_array($tag, ['ul', 'ol'], true)) {
            $count = 0;
            foreach ($node->childNodes as $child) {
                if ($this->isElement($child) && strtolower($child->tagName) === 'li') $count++;
            }
            return $count * $this->config['li_height'] + 8;
        }

        // Generic block: measure text content
        $text    = $node->textContent;
        $padding = in_array($tag, ['blockquote'], true)
            ? $this->config['blockquote_padding']
            : $this->config['paragraph_padding'];

        return $this->estimateHeightFromText($text, $tag) + $padding;
    }

    private function estimateHeightFromText(string $text, string $tag = 'p'): int
    {
        $charCount = mb_strlen(trim($text));
        if ($charCount === 0) return $this->config['line_height'];

        $lines = max(1, (int) ceil($charCount / $this->config['chars_per_line']));
        return $lines * $this->config['line_height'];
    }

    private function estimateTableHeight(DOMNode $table): int
    {
        $rows    = $table->getElementsByTagName('tr')->length;
        $hasHead = $table->getElementsByTagName('thead')->length > 0;
        $rowH    = $hasHead
            ? ($rows - 1) * $this->config['table_row_height'] + $this->config['table_header_height']
            : $rows * $this->config['table_row_height'];
        return $rowH + $this->config['table_border_padding'];
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function isSplittable(string $tag): bool
    {
        return in_array($tag, [
            'table', 'p', 'div', 'section', 'article', 'main',
            'blockquote', 'ul', 'ol',
        ], true);
    }

    private function isAtPageTop(int $cursor): bool
    {
        return $cursor <= $this->marginTop + 5;
    }

    private function isElement(DOMNode $node): bool
    {
        return $node->nodeType === XML_ELEMENT_NODE;
    }

    /**
     * Build an opening tag string, preserving all original attributes.
     */
    private function openTag(DOMNode $node): string
    {
        $tag  = strtolower($node->tagName);
        $html = '<' . $tag;
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $html .= ' ' . $attr->name . '="' . htmlspecialchars($attr->value, ENT_QUOTES) . '"';
            }
        }
        return $html . '>';
    }

    private function loadDom(string $htmlContent): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        $wrapped = '<?xml encoding="UTF-8">'
            . '<html><body><div id="__paginator_root__">'
            . $htmlContent
            . '</div></body></html>';

        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        return $dom;
    }

    private function wrapPage(string $content): string
    {
        return '<div class="print-page">' . $content . '</div>';
    }
}