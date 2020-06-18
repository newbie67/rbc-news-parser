<?php

declare(strict_types=1);

namespace App\Service\RbcParser;

use App\DTO\RbcImage;
use App\DTO\RbcPost;
use App\DTO\RbcPostLink;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Достаёт информацию по одному посту
 */
class RbcOnePostParser
{
    /**
     * @var string[]
     */
    private array $articleHeaderSelectors = [
        '.article .article__header .article__header__title > h1',
        '.article .article__header .article__header__title > span',
    ];

    /**
     * @var string[]
     */
    private array $articleImageSelectors = [
        '.article .article__main-image img',
    ];

    /**
     * @var string[]
     */
    private array $subTitleSelectors = [
        '.article .article__content .article__text__overview span',
    ];

    /**
     * @var string[]
     */
    private array $textSelectors = [
        '.article .article__content .article__text > p',
    ];

    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

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
        $parsedUrl = parse_url($postLink->getUrl());

        // Сразу отбросим новости из раздела "про" и все лендинги
        if ($parsedUrl['host'] === 'pro.rbc.ru' || substr($parsedUrl['host'], -6) !== 'rbc.ru') {
            return null;
        }

        $response = $this->client->get($postLink->getUrl());
        $crawler = new Crawler($response->getBody()->__toString());

        $title = $crawler->filter(implode(', ', $this->articleHeaderSelectors));
        $image = $crawler->filter(implode(', ', $this->articleImageSelectors));
        $subTitle = $crawler->filter(implode(', ', $this->subTitleSelectors));
        $textParagraphs = $crawler->filter(implode(', ', $this->textSelectors));

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
