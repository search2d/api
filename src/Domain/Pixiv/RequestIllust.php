<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

class RequestIllust
{
    /** @var int */
    private $illustId;

    /**
     * @param int $illustId
     */
    public function __construct(int $illustId)
    {
        $this->illustId = $illustId;
    }

    /**
     * @return int
     */
    public function getIllustId(): int
    {
        return $this->illustId;
    }
}