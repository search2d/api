<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

interface NnsRepository
{
    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param int $radius
     * @param int $count
     * @return \Search2d\Domain\Search\NnsPointCollection
     */
    public function search(Sha1 $sha1, int $radius, int $count): NnsPointCollection;

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return void
     */
    public function upsert(Sha1 $sha1): void;
}