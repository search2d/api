<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

class IllustPage
{
    /** @var int */
    private $offset;

    /** @var string */
    private $imageUrl;

    /**
     * @param int $offset
     * @param string $imageUrl
     */
    public function __construct(int $offset, string $imageUrl)
    {
        $this->offset = $offset;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}