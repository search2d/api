<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class AuthResponseUser
{
    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\AuthResponseUserProfileImageUrls
     * @required
     */
    public $profile_image_urls;

    /**
     * @var string
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
     * @var string
     * @required
     */
    public $mail_address;

    /**
     * @var bool
     * @required
     */
    public $is_premium;

    /**
     * @var int
     * @required
     */
    public $x_restrict;

    /**
     * @var bool
     * @required
     */
    public $is_mail_authorized;
}