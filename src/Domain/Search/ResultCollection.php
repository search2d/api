<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

use ArrayIterator;
use Traversable;

class ResultCollection implements \IteratorAggregate, \Countable
{
    /** @var \Search2d\Domain\Search\Result[] */
    private $values = [];

    /**
     * @param \Search2d\Domain\Search\Result[] $values
     */
    public function __construct(array $values)
    {
        usort($values, function (Result $a, Result $b): int {
            return $a->getDistance() <=> $b->getDistance();
        });

        $this->values = $values;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->values);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->values);
    }
}