<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class UserDetail
{
    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetailUser
     * @required
     */
    public $user;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetailProfile
     * @required
     */
    public $profile;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetailProfilePublicity
     * @required
     */
    public $profile_publicity;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetailWorkspace
     * @required
     */
    public $workspace;
}