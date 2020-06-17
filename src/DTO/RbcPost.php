<?php

declare(strict_types=1);

namespace App\DTO;

final class RbcPost
{
    /**
     * @var RbcPostLink
     */
    private RbcPostLink $postLink;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $text;

    /**
     * @var RbcImage|null
     */
    private ?RbcImage $image;

    /**
     * RbcPostDTO constructor.
     *
     * @param RbcPostLink $postLink
     * @param string $title
     * @param string $text
     * @param RbcImage|null $image
     */
    public function __construct(
        RbcPostLink $postLink,
        string $title,
        string $text,
        ?RbcImage $image
    ) {
        $this->postLink = $postLink;
        $this->title = $title;
        $this->text = $text;
        $this->image = $image;
    }

    /**
     * @return RbcPostLink
     */
    public function getPostLink(): RbcPostLink
    {
        return $this->postLink;
    }

//    /**
//     * @return RbcPostLink
//     */
//    public function getPostLink(): RbcPostLink
//    {
//        return $this->postLink;
//    }
//
//    /**
//     * @return string
//     */
//    public function getTitle(): string
//    {
//        return $this->title;
//    }
//
//    /**
//     * @return string
//     */
//    public function getText(): string
//    {
//        return $this->text;
//    }
}
