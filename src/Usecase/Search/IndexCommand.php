<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Search2d\Domain\Search\Detail;
use Search2d\Domain\Search\Image;

class IndexCommand
{
    /** @var \Search2d\Domain\Search\Image */
    public $image;

    /** @var \Search2d\Domain\Search\Detail */
    public $detail;

    /**
     * @param \Search2d\Domain\Search\Image $image
     * @param \Search2d\Domain\Search\Detail $detail
     */
    public function __construct(Image $image, Detail $detail)
    {
        $this->image = $image;
        $this->detail = $detail;
    }
}