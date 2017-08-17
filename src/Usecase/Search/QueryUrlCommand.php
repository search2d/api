<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

class QueryUrlCommand
{
    /** @var string */
    public $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }
}