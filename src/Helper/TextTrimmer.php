<?php

declare(strict_types=1);

namespace App\Helper;

use App\Entity\Post;
use DomDocument;
use DomNode;
use DomText;

class TextTrimmer
{
    /**
     * @var bool[]
     */
    private array $reachedLimit = [];

    /**
     * @var int[]
     */
    private array $totalLen = [];

    /**
     * @var DomNode[]
     */
    private array $toRemove = [];

    /**
     * @param Post $post
     * @param int $limit
     *
     * @return string
     */
    public function trim(Post $post, int $limit = 25): string
    {
        $dom = new DomDocument();

        $dom->loadHTML(
            mb_convert_encoding($post->getText(), 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR
        );

        $this->totalLen[$post->getId()] = 0;
        $this->toRemove[$post->getId()] = [];
        $toRemove = $this->walk($post->getId(), $dom, $limit);

        // remove any nodes that exceed limit
        foreach ($toRemove as $child) {
            $child->parentNode->removeChild($child);
        }

        return $dom->saveHTML();
    }

    /**
     * @param int $id
     * @param DomNode $node
     * @param $limit
     *
     * @return mixed
     */
    private function walk(int $id, DomNode $node, $limit)
    {
        if (array_key_exists($id, $this->reachedLimit) && $this->reachedLimit[$id]) {
            $this->toRemove[$id][] = $node;
        } else {
            // only text nodes should have text,
            // so do the splitting here
            if ($node instanceof DomText) {
                $this->totalLen[$id] += $nodeLen = mb_strlen($node->nodeValue);

                if ($this->totalLen[$id] > $limit) {
                    $node->nodeValue = mb_substr(
                        $node->nodeValue,
                        0,
                        $nodeLen - ($this->totalLen[$id] - $limit)
                    ) . '...';
                    $this->reachedLimit[$id] = true;
                }
            }

            // if node has children, walk its child elements
            if (isset($node->childNodes)) {
                foreach ($node->childNodes as $child) {
                    $this->walk($id, $child, $limit);
                }
            }
        }

        return $this->toRemove[$id];
    }
}
