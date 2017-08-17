<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Psr\Log\LoggerInterface;
use Search2d\Domain\Search\IndexedImageRepository;
use Search2d\Domain\Search\NnsRepository;
use Search2d\Domain\Search\QueriedImageRepository;
use Search2d\Domain\Search\Result;
use Search2d\Domain\Search\ResultCollection;
use Search2d\Domain\Search\Sha1;

class SearchHandler
{
    /** @var \Search2d\Domain\Search\QueriedImageRepository */
    private $queriedImageRepository;

    /** @var \Search2d\Domain\Search\IndexedImageRepository */
    private $indexedImageRepository;

    /** @var \Search2d\Domain\Search\NnsRepository */
    private $nnsRepository;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Search2d\Domain\Search\QueriedImageRepository $queriedImageRepository
     * @param \Search2d\Domain\Search\IndexedImageRepository $indexedImageRepository
     * @param \Search2d\Domain\Search\NnsRepository $nnsRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        QueriedImageRepository $queriedImageRepository,
        IndexedImageRepository $indexedImageRepository,
        NnsRepository $nnsRepository,
        LoggerInterface $logger
    )
    {
        $this->queriedImageRepository = $queriedImageRepository;
        $this->indexedImageRepository = $indexedImageRepository;
        $this->nnsRepository = $nnsRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Search2d\Usecase\Search\SearchCommand $command
     * @return \Search2d\Domain\Search\ResultCollection
     */
    public function __invoke(SearchCommand $command): ResultCollection
    {
        $query = $this->queriedImageRepository->find($command->sha1);
        if (!$query) {
            throw new \RuntimeException();
        }

        return $this->search($query->getSha1(), $command->radius, $command->count);
    }

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param int $radius
     * @param int $count
     * @return \Search2d\Domain\Search\ResultCollection
     */
    private function search(Sha1 $sha1, int $radius, int $count): ResultCollection
    {
        $points = $this->nnsRepository->search($sha1, $radius, $count);

        $results = [];

        /** @var \Search2d\Domain\Search\NnsPoint $point */
        foreach ($points as $point) {
            $indexedImage = $this->indexedImageRepository->find($point->getSha1());
            if (!$indexedImage) {
                $this->logger->alert(sprintf('%sがインデックスされていない', $point->getSha1()->value));
                continue;
            }

            $results[] = new Result($indexedImage, $point->getDistance());
        }

        return new ResultCollection($results);
    }
}