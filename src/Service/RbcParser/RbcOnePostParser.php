<?php

declare(strict_types=1);

namespace App\Service\RbcParser;

use App\DTO\RbcImage;
use App\DTO\RbcPost;
use App\DTO\RbcPostLink;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Достаёт информацию по одному посту
 */
class RbcOnePostParser
{
    /**
     * RbcOnePostParser constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param RbcPostLink $postLink
     *
     * @return RbcPost|null
     */
    public function getPost(RbcPostLink $postLink): ?RbcPost
    {
        $response = $this->client->get($postLink->getUrl());
        $crawler = new Crawler($response->getBody()->__toString());

        $title = $crawler->filter('.article .article__header .article__header__title > h1');
        $image = $crawler->filter('.article .article__main-image img');
        $subTitle = $crawler->filter('.article .article__content .article__text__overview span');
        $textParagraphs = $crawler->filter('.article .article__content .article__text > p');

        if ($title->count() === 1 && $textParagraphs->count() > 0) {
            $texts = [];
            if ($subTitle->count()) {
                $texts[] = trim($subTitle->text());
            }
            $textParagraphs->each(function (Crawler $node, $key) {
                $texts[] = trim($node->html());
            });
            $image = $image->count() ? new RbcImage($image->attr('src'), $image->attr('alt')) : null;

            return new RbcPost(
                $postLink,
                $title->text(),
                '<p>' . implode("</p>\r\n<p>", $texts) . '</p>',
                $image
            );
        } else {
            // Значит эта запись не похожа на новость
            return null;
        }
    }
}
