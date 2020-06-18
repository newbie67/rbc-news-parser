<?php

declare(strict_types=1);

namespace App\Tests\Service\RbcParser;

use App\DTO\RbcImage;
use App\DTO\RbcPost;
use App\DTO\RbcPostLink;
use App\Service\RbcParser\RbcOnePostParser;
use PHPUnit\Framework\TestCase;

class OnePostParserTest extends TestCase
{
    /**
     * @var RbcOnePostParser
     */
    private RbcOnePostParser $onePostParser;

    /**
     * @dataProvider rbcPostLinksProvider
     *
     * @param RbcPostLink $postLink
     * @param RbcPost|null $expected
     */
    public function testGetPost(RbcPostLink $postLink, ?RbcPost $expected)
    {
        $result = $this->onePostParser->getPost($postLink);

        if ($expected === null) {
            $this->assertNull($result);
        } else {
            $this->assertInstanceOf(get_class($expected), $result);

            $this->assertEquals(
                $expected->getImage()->getUrl(),
                $result->getImage()->getUrl()
            );

            $this->assertEquals(
                $expected->getImage()->getDescription(),
                $result->getImage()->getDescription()
            );

            $this->assertEquals(
                $expected->getTitle(),
                $result->getTitle()
            );
        }
    }

    /**
     * @return array
     *
     * @todo Actual
     */
    public function rbcPostLinksProvider(): array
    {
        $correctData = json_decode(file_get_contents(__DIR__ . '/../../data/rbc-news.json'));
        $correctPostLinks = [];
        foreach ($correctData as $item) {
            $correctPostLinks[] = [
                new RbcPostLink($item->url, $item->dateModify),
                new RbcPost(
                    new RbcPostLink($item->url, $item->dateModify),
                    $item->title,
                    $item->text,                                                                    // todo
                    new RbcImage($item->imageSrc, $item->imageDescription)
                ),
            ];
        }

        return array_merge([
            [
                // Param
                new RbcPostLink(
                    'http://bcs.rbc.ru/article/2',
                    1592480738
                ),
                // Expected
                null,
            ],
            [
                new RbcPostLink(
                    'https://pro.rbc.ru/news/5eea4d409a79474a36412b73',
                    1592481709
                ),
                null,
            ],
        ], $correctPostLinks);
////            [
////                // Param
////                new RbcPostLink(
////                    'https://www.rbc.ru/economics/18/06/2020/5eeb52c79a79473363e6f0e8',
////
////                ),
////                // Expected
////                new RbcPost(
////                    new RbcPostLink(
////                        'https://www.rbc.ru/economics/18/06/2020/5eeb52c79a79473363e6f0e8',
////                        1592483113
////                    ),
////                    '',
////                    'asdf',                                                                             // todo
////                    new RbcImage(
////                        '',
////                        ''
////                    )
////                ),
////            ],
////            [
////                new RbcPostLink(
////                    '',
////
////                ),
////                // Expected
////                new RbcPost(
////                    new RbcPostLink(
////                        'https://sport.rbc.ru/news/5eeb56029a794735a855e90d',
////                        1592482024
////                    ),
////                    '',
////                    'asdf',                                                                             // todo
////                    new RbcImage(
////                        '',
////                        ''
////                    )
////                ),
////            ],
//
//
//        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->onePostParser = new RbcOnePostParser();
    }
}
