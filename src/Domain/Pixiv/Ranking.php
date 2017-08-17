<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

class Ranking
{
    /** @var \Search2d\Domain\Pixiv\RankingMode */
    private $mode;

    /** @var \Search2d\Domain\Pixiv\RankingDate */
    private $date;

    /** @var \Search2d\Domain\Pixiv\RankingIllustCollection */
    private $illusts;

    /**
     * @param \Search2d\Domain\Pixiv\RankingMode $mode
     * @param \Search2d\Domain\Pixiv\RankingDate $date
     * @param \Search2d\Domain\Pixiv\RankingIllustCollection $illusts
     */
    public function __construct(RankingMode $mode, RankingDate $date, RankingIllustCollection $illusts)
    {
        $this->mode = $mode;
        $this->date = $date;
        $this->illusts = $illusts;
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

    /**
     * @return \Search2d\Domain\Pixiv\RankingIllustCollection
     */
    public function getIllusts(): RankingIllustCollection
    {
        return $this->illusts;
    }
}