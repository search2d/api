<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use Aws\S3\S3Client;
use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\IndexedImageStorage;
use Search2d\Domain\Search\QueriedImageStorage;

class S3ImageStorage implements IndexedImageStorage, QueriedImageStorage
{
    /** @var \Aws\S3\S3Client */
    private $s3Client;

    /** @var string */
    private $bucket;

    /**
     * @param \Aws\S3\S3Client $s3Client
     * @param string $bucket
     */
    public function __construct(S3Client $s3Client, string $bucket)
    {
        $this->s3Client = $s3Client;

        assert(strlen($bucket) > 0);
        $this->bucket = $bucket;
    }

    /**
     * @param \Search2d\Domain\Search\Image $image
     * @return void
     */
    public function upload(Image $image): void
    {
        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $image->getSha1()->value,
            'Body' => $image->getData(),
            'ContentType' => $image->getMime()->value,
        ]);
    }
}