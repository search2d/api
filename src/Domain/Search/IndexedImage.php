<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

use Cake\Chronos\ChronosInterface;

class IndexedImage
{
    /** @var \Search2d\Domain\Search\Sha1 */
    private $sha1;

    /** @var \Search2d\Domain\Search\Mime */
    private $mime;

    /** @var int */
    private $size;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var string */
    private $imageUrl;

    /** @var string */
    private $pageUrl;

    /** @var string */
    private $pageTitle;

    /** @var \Cake\Chronos\ChronosInterface */
    private $crawledAt;

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param \Search2d\Domain\Search\Mime $mime
     * @param int $size
     * @param int $width
     * @param int $height
     * @param string $imageUrl
     * @param string $pageUrl
     * @param string $pageTitle
     * @param \Cake\Chronos\ChronosInterface $crawledAt
     */
    public function __construct(
        Sha1 $sha1,
        Mime $mime,
        int $size,
        int $width,
        int $height,
        string $imageUrl,
        string $pageUrl,
        string $pageTitle,
        ChronosInterface $crawledAt
    )
    {
        assert($crawledAt->getTimezone()->getName() === 'UTC');

        $this->sha1 = $sha1;
        $this->mime = $mime;
        $this->size = $size;
        $this->width = $width;
        $this->height = $height;
        $this->imageUrl = $imageUrl;
        $this->pageUrl = $pageUrl;
        $this->pageTitle = $pageTitle;
        $this->crawledAt = $crawledAt;
    }

    /**
     * @return \Search2d\Domain\Search\Sha1
     */
    public function getSha1(): Sha1
    {
        return $this->sha1;
    }

    /**
     * @return \Search2d\Domain\Search\Mime
     */
    public function getMime(): Mime
    {
        return $this->mime;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getPageUrl(): string
    {
        return $this->pageUrl;
    }

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * @return \Cake\Chronos\ChronosInterface
     */
    public function getCrawledAt(): ChronosInterface
    {
        return $this->crawledAt;
    }
}