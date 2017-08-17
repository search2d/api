<?php

namespace Search2d\Domain\Pixiv;

use Search2d\Domain\Search\Image;

interface RemoteRepository
{
    /**
     * @param int $illustId
     * @return \Search2d\Domain\Pixiv\Illust
     */
    public function getIllust(int $illustId): Illust;

    /**
     * @param string $url
     * @return \Search2d\Domain\Search\Image
     */
    public function getImage(string $url): Image;

    /**
     * @param \Search2d\Domain\Pixiv\RankingMode $mode
     * @param \Search2d\Domain\Pixiv\RankingDate $date
     * @return \Search2d\Domain\Pixiv\Ranking
     */
    public function getRanking(RankingMode $mode, RankingDate $date): Ranking;
}