<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

class RequestRanking
{
    /** @var \Search2d\Domain\Pixiv\RankingMode */
    private $mode;

    /** @var \Search2d\Domain\Pixiv\RankingDate */
    private $date;

    /**
     * @param \Search2d\Domain\Pixiv\RankingMode $mode
     * @param \Search2d\Domain\Pixiv\RankingDate $date
     */
    public function __construct(RankingMode $mode, RankingDate $date)
    {
        $this->mode = $mode;
        $this->date = $date;
    }

    /**
     * @return \Search2d\Domain\Pixiv\RankingMode
     */
    public function getMode(): RankingMode
    {
        return $this->mode;
    }

    /**
     * @return \Search2d\Domain\Pixiv\RankingDate
     */
    public function getDate(): RankingDate
    {
        return $this->date;
    }
}