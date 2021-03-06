<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class IllustDetailIllustImageUrls
{
    /**
     * @var string
     * @required
     */
    public $square_medium;

    /**
     * @var string
     * @required
     */
    public $medium;

    /**
     * @var string
     * @required
     */
    public $large;
}