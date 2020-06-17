<?php

declare(strict_types=1);

namespace App\Service\RbcParser;

use App\DTO\RbcPost;
use App\DTO\RbcPostLink;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Парсит новости из ленты с html главной страницы
 *
 * @package App\Service
 */
class RbcPostsDomParser
{
    private const URL = 'https://rbc.ru/';

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
     * @return RbcPost[]
     */
    public function getPosts(): array
    {
        $response = $this->client->get(self::URL);
        $crawler = new Crawler($response->getBody()->__toString());

        $links = $crawler->filter('#js_news_feed_banner > .js-news-feed-list > a.news-feed__item')->each(
            function (Crawler $node, $key) {
                /** @var Crawler $node */
                return new RbcPostLink(
                    $node->attr('href'),
                    (int)$node->attr('data-modif')
                );
            }
        );

        $rbcPosts = [];
        foreach ($links as $link) {
            $rbcPost = $this->onePostParser->getPost($link);
            if ($rbcPost !== null) {
                $rbcPosts[] = $rbcPost;
            }
        }

        return $rbcPosts;
    }
}
