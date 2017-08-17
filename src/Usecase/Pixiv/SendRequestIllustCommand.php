<?php
declare(strict_types=1);

namespace Search2d\Usecase\Pixiv;

class SendRequestIllustCommand
{
    /** @var int */
    public $illustId;

    /**
     * @param int $illustId
     */
    public function __construct(int $illustId)
    {
        $this->illustId = $illustId;
    }
}