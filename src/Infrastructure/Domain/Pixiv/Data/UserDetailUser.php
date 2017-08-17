<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class UserDetailUser
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
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetailUserProfileImageUrls
     * @required
     */
    public $profile_image_urls;

    /**
     * @var string
     * @required
     */
    public $comment;

    /**
     * @var bool
     * @required
     */
    public $is_followed;
}