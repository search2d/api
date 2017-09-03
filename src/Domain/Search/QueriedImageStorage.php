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
}