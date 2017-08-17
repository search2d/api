<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use Aws\S3\S3Client;
use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\IndexedImageStorage;
use Search2d\Domain\Search\QueriedImageStorage;
use Search2d\Domain\Search\Sha1;

class S3ImageStorage implements IndexedImageStorage, QueriedImageStorage
{
    /** @var \Aws\S3\S3Client */
    private $s3Client;

    /** @var string */
    private $bucket;

    /** @var string */
    private $baseUrl;

    /**
     * @param \Aws\S3\S3Client $s3Client
     * @param string $bucket
     * @param string $baseUrl
     */
    public function __construct(S3Client $s3Client, string $bucket, string $baseUrl)
    {
        $this->s3Client = $s3Client;

        assert(strlen($bucket) > 0);
        $this->bucket = $bucket;

        assert(strlen($baseUrl) > 0);
        assert(substr($baseUrl, -1, 1) !== '/');
        $this->baseUrl = $baseUrl;
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

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return string
     */
    function export(Sha1 $sha1): string
    {
        return $this->baseUrl . '/' . $sha1->value;
    }
}