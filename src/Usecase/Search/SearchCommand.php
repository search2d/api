<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Search2d\Domain\Search\Sha1;

class SearchCommand
{
    /** @var \Search2d\Domain\Search\Sha1 */
    public $sha1;

    /** @var int */
    public $radius;

    /** @var int */
    public $count;

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param int $radius
     * @param int $count
     */
    public function __construct(Sha1 $sha1, int $radius, int $count)
    {
        $this->sha1 = $sha1;
        $this->radius = $radius;
        $this->count = $count;
    }
}