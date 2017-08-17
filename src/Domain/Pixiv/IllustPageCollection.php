<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

use ArrayIterator;
use Traversable;

class IllustPageCollection implements \IteratorAggregate, \Countable
{
    /** @var \Search2d\Domain\Pixiv\IllustPage[] */
    private $pages = [];

    /**
     * @param \Search2d\Domain\Pixiv\IllustPage[] $pages
     */
    public function __construct(array $pages)
    {
        foreach ($pages as $page) {
            if (isset($this->pages[$page->getOffset()])) {
                throw new \InvalidArgumentException();
            }

            $this->pages[$page->getOffset()] = $page;
        }
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->pages);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->pages);
    }
}