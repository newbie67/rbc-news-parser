<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\RbcParser\RbcPostsAjaxParser;
use App\Service\RbcParser\RbcPostsDomParser;
use Exception;

/**
 * Class RbkNewsDomParser
 *
 * Достаёт новости с сайта rbc.ru
 *
 * @package App\Service
 */
class RbcParser
{
    const POST_COUNT = 15;

    /**
     * @var RbcPostsDomParser
     */
    private RbcPostsDomParser $rbcPostsDomParser;

    /**
     * @var RbcPostsAjaxParser
     */
    private RbcPostsAjaxParser $rbcPostsAjaxParser;

    /**
     * RbcParser constructor.
     *
     * @param RbcPostsDomParser $rbcPostsDomParser
     * @param RbcPostsAjaxParser $rbcPostsAjaxParser
     */
    public function __construct(
        RbcPostsDomParser $rbcPostsDomParser,
        RbcPostsAjaxParser $rbcPostsAjaxParser
    ) {
        $this->rbcPostsDomParser = $rbcPostsDomParser;
        $this->rbcPostsAjaxParser = $rbcPostsAjaxParser;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getFeedPosts(): array
    {
        $elements = $this->rbcPostsDomParser->getPosts();

        while (count($elements) < self::POST_COUNT) {
            $lastElement = end($elements);
            $lastElementModifyDate = $lastElement->getPostLink()->getDateModify();
            $elements = array_merge($elements, $this->rbcPostsAjaxParser->getPosts(
                $lastElementModifyDate,
                self::POST_COUNT - count($elements)
            ));
        }

        return $elements;
    }
}
