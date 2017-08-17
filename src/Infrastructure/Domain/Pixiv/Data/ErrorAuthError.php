<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class ErrorAuthError
{
    /**
     * @var string
     * @required
     */
    public $message;

    /**
     * @var int
     * @required
     */
    public $code;
}