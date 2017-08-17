<?php
declare(strict_types=1);

namespace Search2d\Usecase\Pixiv;

use Search2d\Domain\Pixiv\RankingDate;
use Search2d\Domain\Pixiv\RankingMode;

class SendRequestRankingCommand
{
    /** @var \Search2d\Domain\Pixiv\RankingMode */
    public $mode;

    /** @var \Search2d\Domain\Pixiv\RankingDate */
    public $date;

    /**
     * @param \Search2d\Domain\Pixiv\RankingMode $mode
     * @param \Search2d\Domain\Pixiv\RankingDate $date
     */
    public function __construct(RankingMode $mode, RankingDate $date)
    {
        $this->mode = $mode;
        $this->date = $date;
    }
}