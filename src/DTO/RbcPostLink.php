<?php

declare(strict_types=1);

namespace App\DTO;

use DateTime;

final class RbcPostLink
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @var DateTime
     */
    private DateTime $dateModify;

    /**
     * RbcLinkDTO constructor.
     *
     * @param string $url
     * @param int $dateModify
     */
    public function __construct(
        string $url,
        int $dateModify
    ) {
        $urlParts = parse_url($url);
        $this->url = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];;
        $this->dateModify = (new DateTime())->setTimestamp($dateModify);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return DateTime
     */
    public function getDateModify(): DateTime
    {
        return $this->dateModify;
    }
}
