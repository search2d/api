<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

class NnsSearchResponsePoint
{
    /**
     * @var string
     * @required
     */
    public $sha1;

    /**
     * @var int
     * @required
     */
    public $dist;
}