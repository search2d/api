<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

class NnsPoint
{
    /** @var \Search2d\Domain\Search\Sha1 */
    private $sha1;

    /** @var int */
    private $distance;

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param int $distance
     */
    public function __construct(Sha1 $sha1, $distance)
    {
        $this->sha1 = $sha1;
        $this->distance = $distance;
    }

    /**
     * @return \Search2d\Domain\Search\Sha1
     */
    public function getSha1(): Sha1
    {
        return $this->sha1;
    }

    /**
     * @return int
     */
    public function getDistance(): int
    {
        return $this->distance;
    }
}