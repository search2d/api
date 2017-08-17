<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Search2d\Domain\Search\Image;

class QueryImgCommand
{
    /** @var \Search2d\Domain\Search\Image */
    public $image;

    /**
     * @param \Search2d\Domain\Search\Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }
}