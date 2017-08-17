<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

interface QueriedImageStorage
{
    /**
     * @param \Search2d\Domain\Search\Image $image
     * @return void
     */
    function upload(Image $image): void;

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return string
     */
    function export(Sha1 $sha1): string;
}