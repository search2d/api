<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Cake\Chronos\ChronosInterface;
use Search2d\Domain\Search\Image;

class IndexCommand
{
    /** @var \Search2d\Domain\Search\Image */
    public $image;

    /** @var string */
    public $imageUrl;

    /** @var string */
    public $pageUrl;

    /** @var string */
    public $pageTitle;

    /** @var \Cake\Chronos\ChronosInterface */
    public $crawledAt;

    /**
     * @param \Search2d\Domain\Search\Image $image
     * @param string $imageUrl
     * @param string $pageUrl
     * @param string $pageTitle
     * @param \Cake\Chronos\ChronosInterface $crawledAt
     */
    public function __construct(Image $image, string $imageUrl, string $pageUrl, string $pageTitle, ChronosInterface $crawledAt)
    {
        $this->image = $image;
        $this->imageUrl = $imageUrl;
        $this->pageUrl = $pageUrl;
        $this->pageTitle = $pageTitle;
        $this->crawledAt = $crawledAt;
    }
}