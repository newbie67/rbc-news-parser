<?php

declare(strict_types=1);

namespace App\Service\RbcParser;

use App\DTO\RbcPost;
use App\DTO\RbcPostLink;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Парсит новости из ленты с html главной страницы
 *
 * @package App\Service
 */
class RbcPostsAjaxParser
{
    const URL = 'https://www.rbc.ru/v10/ajax/get-news-feed/project/rbcnews/lastDate/{dateModify}/limit/11';

    /**
     * @var RbcOnePostParser
     */
    private RbcOnePostParser $onePostParser;

    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * RbcPostsDomParser constructor.
     *
     * @param RbcOnePostParser $onePostParser
     */
    public function __construct(RbcOnePostParser $onePostParser)
    {
        $this->onePostParser = $onePostParser;
        $this->client = new Client();
    }

    /**
     * @param DateTime $dateAfter Дата после которой получать посты
     * @param int $num Количество постов
     *
     * @return RbcPost[]
     *
     * @throws \Exception
     */
    public function getPosts(DateTime $dateAfter, int $num): array
    {
        $response = $this->client->get(str_replace('{dateModify}', $dateAfter->getTimestamp(), self::URL));
        $response = json_decode($response->getBody()->__toString());

        $posts = [];
        foreach ($response->items as $item) {
            $link = (new Crawler($item->html))->filter('body > a');
            $postLink = new RbcPostLink($link->attr('href'),
                (int)$link->attr('data-modif')
            );
            $post = $this->onePostParser->getPost($postLink);
            if ($post !== null) {
                $posts[] = $post;
            }

            if (count($posts) === $num) {
                return $posts;
            }
        }

        return $posts;
    }
}
