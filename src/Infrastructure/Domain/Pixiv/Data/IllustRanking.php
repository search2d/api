<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv\Data;

class IllustRanking
{
    /**
     * @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRankingIllust[]
     * @required
     */
    public $illusts;

    /**
     * @var string|null
     * @required
     */
    public $next_url;
}