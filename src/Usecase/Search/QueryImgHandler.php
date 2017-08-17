<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Search2d\Domain\Search\QueriedImage;
use Search2d\Domain\Search\QueriedImageRepository;
use Search2d\Domain\Search\QueriedImageStorage;

class QueryImgHandler
{
    /** @var \Search2d\Domain\Search\QueriedImageRepository */
    private $queriedImageRepository;

    /** @var \Search2d\Domain\Search\QueriedImageStorage */
    private $queriedImageStorage;

    /**
     * @param \Search2d\Domain\Search\QueriedImageRepository $queriedImageRepository
     * @param \Search2d\Domain\Search\QueriedImageStorage $queriedImageStorage
     */
    public function __construct(QueriedImageRepository $queriedImageRepository, QueriedImageStorage $queriedImageStorage)
    {
        $this->queriedImageRepository = $queriedImageRepository;
        $this->queriedImageStorage = $queriedImageStorage;
    }

    /**
     * @param \Search2d\Usecase\Search\QueryImgCommand $command
     * @return \Search2d\Domain\Search\QueriedImage
     */
    public function __invoke(QueryImgCommand $command): QueriedImage
    {
        $query = QueriedImage::create($command->image);

        $this->queriedImageRepository->save($query);

        $this->queriedImageStorage->upload($command->image);

        return $query;
    }
}