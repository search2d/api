<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

class Result
{
    /** @var \Search2d\Domain\Search\IndexedImage */
    private $indexedImage;

    /** @var int */
    private $distance;

    /**
     * @param \Search2d\Domain\Search\IndexedImage $indexedImage
     * @param int $distance
     */
    public function __construct(IndexedImage $indexedImage, $distance)
    {
        $this->indexedImage = $indexedImage;
        $this->distance = $distance;
    }

    /**
     * @return \Search2d\Domain\Search\IndexedImage
     */
    public function getIndexedImage(): IndexedImage
    {
        return $this->indexedImage;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }
}