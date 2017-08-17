<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class IllustDetailIllustUser
{
    /**
     * @var int
     * @required
     */
    public $id;

    /**
     * @var string
     * @required
     */
    public $name;

    /**
     * @var string
     * @required
     */
    public $account;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetailIllustUserProfileImageUrls
     * @required
     */
    public $profile_image_urls;

    /**
     * @var bool
     * @required
     */
    public $is_followed;
}