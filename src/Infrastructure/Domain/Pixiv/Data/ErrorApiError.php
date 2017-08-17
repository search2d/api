<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class ErrorApiError
{
    /**
     * @var string
     * @required
     */
    public $user_message;

    /**
     * @var string
     * @required
     */
    public $message;

    /**
     * @var string
     * @required
     */
    public $reason;

    /**
     * @var \StdClass
     * @required
     */
    public $user_message_details;
}