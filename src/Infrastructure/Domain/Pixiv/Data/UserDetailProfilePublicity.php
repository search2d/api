<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class UserDetailProfilePublicity
{
    /**
     * @var string
     * @required
     */
    public $gender;

    /**
     * @var string
     * @required
     */
    public $region;

    /**
     * @var string
     * @required
     */
    public $birth_day;

    /**
     * @var string
     * @required
     */
    public $birth_year;

    /**
     * @var string
     * @required
     */
    public $job;

    /**
     * @var bool
     * @required
     */
    public $pawoo;
}