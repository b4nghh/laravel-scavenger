<?php

declare(strict_types=1);

namespace ReliqArts\Scavenger\Helper;

use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

class NodeProximityAssistant
{
    private const CURRENT_NODE_LIST_EMPTY = 'The current node list is empty.';

    public function closest(string $selector, Crawler $crawler): Crawler
    {
        if (!count($crawler)) {
            throw new InvalidArgumentException(self::CURRENT_NODE_LIST_EMPTY);
        }

        $node = $crawler->getNode(0);

        if ($node !== null) {
            while ($node = $node->parentNode) {
                if (XML_ELEMENT_NODE === $node->nodeType) {
                    $parentCrawler = new Crawler($node, $crawler->getUri(), $crawler->getBaseHref());
                    $descendantMatchingSelector = $parentCrawler->filter($selector);
                    if ($descendantMatchingSelector->count()) {
                        return $descendantMatchingSelector;
                    }
                }
            }
        }

        return null;
    }
}
