<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

class JsonRequestRanking
{
    /**
     * @var string
     * @required
     */
    public $mode;

    /**
     * @var string
     * @required
     */
    public $date;
}