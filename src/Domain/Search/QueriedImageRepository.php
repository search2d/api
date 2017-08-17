<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

interface QueriedImageRepository
{
    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return null|\Search2d\Domain\Search\QueriedImage
     */
    public function find(Sha1 $sha1): ?QueriedImage;

    /**
     * @param \Search2d\Domain\Search\QueriedImage $queriedImage
     * @return void
     */
    public function save(QueriedImage $queriedImage): void;
}