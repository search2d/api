<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

use ArrayIterator;
use Traversable;

class NnsPointCollection implements \IteratorAggregate, \Countable
{
    /** @var \Search2d\Domain\Search\NnsPoint[] */
    private $values = [];

    /**
     * @param \Search2d\Domain\Search\NnsPoint[] $values
     */
    public function __construct(array $values)
    {
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