<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

class NnsSearchResponse
{
    /**
     * @var \Search2d\Infrastructure\Domain\Search\NnsSearchResponsePoint[]
     * @required
     */
    public $points;
}