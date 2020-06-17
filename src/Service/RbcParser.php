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
 * В новостной ленте rbc.ru отображается только 14 "новостей",
 * часть из которых не новости, а всякого рода тесты\лендинги вроде таких:
 *  http://cloudmts.rbc.ru/test
 *  https://lockdown.rbc.ru/
 *  Или же ссылки на курсы валют.
 * Под все подобные ресурсы невозможно написать парсер (так как они уникальны),
 * поэтому парсятся только новости.
 *
 * Новостями считаются записи, ведущие на https://rbc.ru.
 * Как показала практика за два дня, это и правда именно новости.
 *
 * В ТЗ не было особых уточнений, но было точно сказано про 15 штук,
 * поэтому сделал так: достаются все новости
 * из этого блока, а затем ajax запросом подтягиваются остальные новости так,
 * как они подтягиваются с сайта rbc ajax-запросом.
 *
 *
 * Конечно же в реальности я бы парсил их RSS ленту/тянул данные из API и там не было бы всех этих проблем.
 * Это задание я понял как задание написать именно парсер.
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
