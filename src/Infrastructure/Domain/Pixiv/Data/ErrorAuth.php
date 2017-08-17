<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class ErrorAuth
{
    /**
     * @var bool
     * @required
     */
    public $has_error;

    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\ErrorAuthErrors
     * @required
     */
    public $errors;
}