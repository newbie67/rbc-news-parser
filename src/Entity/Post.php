<?php

namespace App\Entity;

use App\Helper\TextTrimmer;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use SebastianBergmann\PHPLOC\Log\Text;
use function GuzzleHttp\Psr7\parse_request;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    public const PREVIEW_SIZE = 200;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_src;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_description;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_modify;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $rbc_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImageSrc(): ?string
    {
        return $this->image_src;
    }

    public function setImageSrc(?string $image_src): self
    {
        $this->image_src = $image_src;

        return $this;
    }

    public function getImageDescription(): ?string
    {
        return $this->image_description;
    }

    public function setImageDescription(?string $image_description): self
    {
        $this->image_description = $image_description;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getDateModify(): ?int
    {
        return $this->date_modify;
    }

    public function setDateModify(int $date_modify): self
    {
        $this->date_modify = $date_modify;

        return $this;
    }

    public function getRbcId(): ?string
    {
        return $this->rbc_id;
    }

    public function setRbcId(string $rbc_id): self
    {
        $this->rbc_id = $rbc_id;

        return $this;
    }
}
