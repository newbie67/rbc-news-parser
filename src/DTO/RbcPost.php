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
     * @var string
     */
    private string $rbcId;

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

        $urlParts = explode('/', $this->postLink->getUrl());
        $this->rbcId = end($urlParts);
    }

    /**
     * @return RbcPostLink
     */
    public function getPostLink(): RbcPostLink
    {
        return $this->postLink;
    }

    /**
     * @return RbcImage|null
     */
    public function getImage(): ?RbcImage
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getRbcId(): string
    {
        return $this->rbcId;
    }
}
