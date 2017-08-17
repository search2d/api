<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

use ArrayIterator;
use Traversable;

class RankingIllustCollection implements \IteratorAggregate, \Countable
{
    /** @var \Search2d\Domain\Pixiv\RankingIllust[] */
    private $illusts = [];

    /**
     * @param \Search2d\Domain\Pixiv\RankingIllust[] $illusts
     */
    public function __construct(array $illusts)
    {
        foreach ($illusts as $illust) {
            if (isset($this->illusts[$illust->getOffset()])) {
                throw new \InvalidArgumentException();
            }

            $this->illusts[$illust->getOffset()] = $illust;
        }
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->illusts);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->illusts);
    }
}