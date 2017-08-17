<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

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

    /** @var \Search2d\Domain\Search\Detail */
    private $detail;

    /**
     * @param \Search2d\Domain\Search\Image $image
     * @param \Search2d\Domain\Search\Detail $detail
     * @return \Search2d\Domain\Search\IndexedImage
     */
    public static function create(Image $image, Detail $detail): self
    {
        return new self(
            $image->getSha1(),
            $image->getMime(),
            $image->getSize(),
            $image->getWidth(),
            $image->getHeight(),
            $detail
        );
    }

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param \Search2d\Domain\Search\Mime $mime
     * @param int $size
     * @param int $width
     * @param int $height
     * @param \Search2d\Domain\Search\Detail $detail
     */
    public function __construct(Sha1 $sha1, Mime $mime, int $size, int $width, int $height, Detail $detail)
    {
        $this->sha1 = $sha1;
        $this->mime = $mime;
        $this->size = $size;
        $this->width = $width;
        $this->height = $height;
        $this->detail = $detail;
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
     * @return \Search2d\Domain\Search\Detail
     */
    public function getDetail(): Detail
    {
        return $this->detail;
    }
}