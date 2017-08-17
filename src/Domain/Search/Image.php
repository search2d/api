<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

class Image
{
    /** @var string */
    private $data;

    /** @var \Search2d\Domain\Search\Mime */
    private $mime;

    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var \Search2d\Domain\Search\Sha1 */
    private $sha1;

    /**
     * @param string $data
     * @return \Search2d\Domain\Search\Image
     * @throws \Search2d\Domain\Search\ImageValidationException
     */
    public static function create(string $data): self
    {
        try {
            $mime = Mime::detect($data);
        } catch (MimeDetectionException $exception) {
            throw new ImageValidationException();
        }

        // 戻り値でエラーをハンドルするのでWarningを抑制
        $image = @imagecreatefromstring($data);
        if ($image === false) {
            throw new ImageValidationException();
        }

        $w = imagesx($image);
        $h = imagesy($image);

        imagedestroy($image);

        return new self($data, $mime, $w, $h);
    }

    /**
     * @param string $data
     * @param \Search2d\Domain\Search\Mime $mime
     * @param int $wSize
     * @param int $hSize
     */
    public function __construct(string $data, Mime $mime, int $wSize, int $hSize)
    {
        $this->data = $data;
        $this->mime = $mime;
        $this->width = $wSize;
        $this->height = $hSize;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
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
     * @return \Search2d\Domain\Search\Sha1
     */
    public function getSha1(): Sha1
    {
        return $this->sha1 ?: $this->sha1 = Sha1::create($this->data);
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return strlen($this->data);
    }
}
