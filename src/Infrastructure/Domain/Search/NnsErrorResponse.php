<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

class NnsErrorResponse
{
    /**
     * @var \Search2d\Infrastructure\Domain\Search\NnsErrorResponseError
     * @required
     */
    public $error;
}