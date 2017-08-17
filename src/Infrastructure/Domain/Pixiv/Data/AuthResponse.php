<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class AuthResponse
{
    /**
     * @var string
     * @required
     */
    public $access_token;

    /**
     * @var int
     * @required
     */
    public $expires_in;

    /**
     * @var string
     * @required
     */
    public $token_type;

    /**
     * @var string
     * @required
     */
    public $scope;

    /**
     * @var string
     * @required
     */
    public $refresh_token;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\AuthResponseUser
     * @required
     */
    public $user;
}