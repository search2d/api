<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Search2d\Domain\Search\ImageFetcher;
use Search2d\Domain\Search\QueriedImage;

class QueryUrlHandler
{
    /** @var \Search2d\Domain\Search\ImageFetcher */
    private $imageFetcher;

    /** @var \Search2d\Usecase\Search\QueryImgHandler */
    private $queryImgHandler;

    /**
     * @param \Search2d\Domain\Search\ImageFetcher $imageFetcher
     * @param \Search2d\Usecase\Search\QueryImgHandler $queryImgHandler
     */
    public function __construct(ImageFetcher $imageFetcher, QueryImgHandler $queryImgHandler)
    {
        $this->imageFetcher = $imageFetcher;
        $this->queryImgHandler = $queryImgHandler;
    }

    /**
     * @param \Search2d\Usecase\Search\QueryUrlCommand $command
     * @return \Search2d\Domain\Search\QueriedImage
     */
    public function handle(QueryUrlCommand $command): QueriedImage
    {
        $image = $this->imageFetcher->fetch($command->url);

        return $this->queryImgHandler->handle(new QueryImgCommand($image));
    }
}