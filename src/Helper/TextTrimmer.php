<?php

declare(strict_types=1);

namespace App\Helper;

use DomDocument;
use DomNode;
use DomText;

class TextTrimmer
{
    /**
     * @var bool
     */
    private bool $reachedLimit = false;

    /**
     * @var int
     */
    private int $totalLen = 0;

    /**
     * @var array
     */
    private array $toRemove = [];

    /**
     * @param string $html
     * @param int $maxLen
     *
     * @return string
     */
    public static function trim(string $html, int $maxLen = 25): string
    {
        $dom = new DomDocument();

        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $instance = new static();
        $toRemove = $instance->walk($dom, $maxLen);

        // remove any nodes that exceed limit
        foreach ($toRemove as $child) {
            $child->parentNode->removeChild($child);
        }

        return $dom->saveHTML();
    }

    private function walk(DomNode $node, $maxLen)
    {
        if ($this->reachedLimit) {
            $this->toRemove[] = $node;
        } else {
            // only text nodes should have text,
            // so do the splitting here
            if ($node instanceof DomText) {
                $this->totalLen += $nodeLen = mb_strlen($node->nodeValue);

                if ($this->totalLen > $maxLen) {
                    $node->nodeValue = mb_substr($node->nodeValue, 0, $nodeLen - ($this->totalLen - $maxLen)) . '...';
                    $this->reachedLimit = true;
                }
            }

            // if node has children, walk its child elements
            if (isset($node->childNodes)) {
                foreach ($node->childNodes as $child) {
                    $this->walk($child, $maxLen);
                }
            }
        }

        return $this->toRemove;
    }
}
