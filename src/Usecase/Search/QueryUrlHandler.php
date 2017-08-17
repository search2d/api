<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use League\Tactician\CommandBus;
use Search2d\Domain\Search\ImageFetcher;
use Search2d\Domain\Search\QueriedImage;

class QueryUrlHandler
{
    /** @var \Search2d\Domain\Search\ImageFetcher */
    private $imageFetcher;

    /** @var \League\Tactician\CommandBus */
    private $commandBus;

    /**
     * @param \Search2d\Domain\Search\ImageFetcher $imageFetcher
     * @param \League\Tactician\CommandBus $commandBus
     */
    public function __construct(ImageFetcher $imageFetcher, CommandBus $commandBus)
    {
        $this->imageFetcher = $imageFetcher;
        $this->commandBus = $commandBus;
    }

    /**
     * @param \Search2d\Usecase\Search\QueryUrlCommand $command
     * @return \Search2d\Domain\Search\QueriedImage
     */
    public function __invoke(QueryUrlCommand $command): QueriedImage
    {
        $image = $this->imageFetcher->fetch($command->url);

        return $this->commandBus->handle(new QueryImgCommand($image));
    }
}