<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

interface IndexedImageRepository
{
    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return null|\Search2d\Domain\Search\IndexedImage
     */
    public function find(Sha1 $sha1): ?IndexedImage;

    /**
     * @param \Search2d\Domain\Search\IndexedImage $indexedImage
     * @return void
     */
    public function save(IndexedImage $indexedImage): void;
}