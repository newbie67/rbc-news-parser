<?php

declare(strict_types=1);

namespace App\DTO;

use DateTime;

final class RbcImage
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @var string|null
     */
    private ?string $description;

    /**
     * RbcImage constructor.
     *
     * @param string $url
     * @param string $description
     */
    public function __construct(
        string $url,
        ?string $description
    ) {
        $this->url = $url;
        $this->description = trim($description);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
