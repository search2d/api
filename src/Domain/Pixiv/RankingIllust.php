<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

class RankingIllust
{
    /** @var int */
    private $offset;

    /** @var int */
    private $illustId;

    /**
     * @param int $offset
     * @param int $illustId
     */
    public function __construct($offset, $illustId)
    {
        $this->offset = $offset;
        $this->illustId = $illustId;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getIllustId(): int
    {
        return $this->illustId;
    }
}