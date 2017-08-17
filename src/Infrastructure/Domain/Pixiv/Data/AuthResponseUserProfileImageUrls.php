<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class AuthResponseUserProfileImageUrls
{
    /**
     * @var string
     * @required
     */
    public $px_16x16;

    /**
     * @var string
     * @required
     */
    public $px_50x50;

    /**
     * @var string
     * @required
     */
    public $px_170x170;
}