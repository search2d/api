<?php
declare(strict_types=1);

namespace Search2d\Usecase\Search;

use Search2d\Domain\Search\IndexedImage;
use Search2d\Domain\Search\IndexedImageRepository;
use Search2d\Domain\Search\IndexedImageStorage;
use Search2d\Domain\Search\NnsRepository;

class IndexHandler
{
    /** @var \Search2d\Domain\Search\IndexedImageRepository */
    private $indexedImageRepository;

    /** @var \Search2d\Domain\Search\IndexedImageStorage */
    private $indexedImageStorage;

    /** @var \Search2d\Domain\Search\NnsRepository */
    private $nnsRepository;

    /**
     * @param \Search2d\Domain\Search\IndexedImageRepository $indexedImageRepository
     * @param \Search2d\Domain\Search\IndexedImageStorage $indexedImageStorage
     * @param \Search2d\Domain\Search\NnsRepository $nnsRepository
     */
    public function __construct(
        IndexedImageRepository $indexedImageRepository,
        IndexedImageStorage $indexedImageStorage,
        NnsRepository $nnsRepository
    )
    {
        $this->indexedImageRepository = $indexedImageRepository;
        $this->indexedImageStorage = $indexedImageStorage;
        $this->nnsRepository = $nnsRepository;
    }

    /**
     * @param \Search2d\Usecase\Search\IndexCommand $command
     * @return void
     */
    public function __invoke(IndexCommand $command): void
    {
        $this->indexedImageRepository->save(IndexedImage::create($command->image, $command->detail));

        $this->indexedImageStorage->upload($command->image);

        $this->nnsRepository->upsert($command->image->getSha1());
    }
}