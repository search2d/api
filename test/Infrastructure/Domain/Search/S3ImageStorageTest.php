<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use Aws\CommandInterface;
use Aws\MockHandler;
use Aws\Result;
use Aws\S3\S3Client;
use Psr\Http\Message\RequestInterface;
use Search2d\Domain\Search\Image;
use Search2d\Infrastructure\Domain\Search\S3ImageStorage;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\Search\S3ImageStorage
 */
class S3ImageStorageTest extends TestCase
{
    private const BUCKET = 'bucket';
    private const BASE_URL = 'https://example.com';

    /**
     * @return void
     */
    public function testUpload(): void
    {
        $faker = $this->faker();
        $image = Image::create(file_get_contents($faker->image()));

        $mock = new MockHandler();
        $mock->append(function (CommandInterface $cmd, RequestInterface $req) use ($image) {
            $this->assertSame('PutObject', $cmd->getName());
            $this->assertSame(self::BUCKET, $cmd['Bucket']);
            $this->assertSame((string)$image->getSha1(), $cmd['Key']);
            $this->assertSame((string)$image->getMime(), $cmd['ContentType']);
            $this->assertSame($image->getData(), $cmd['Body']);
            return new Result([]);
        });

        $storage = new S3ImageStorage($this->createClient($mock), self::BUCKET, self::BASE_URL);
        $storage->upload($image);
    }

    /**
     * @param \Aws\MockHandler $mock
     * @return \Aws\S3\S3Client
     */
    private function createClient(MockHandler $mock): S3Client
    {
        return new S3Client([
            'region' => 'ap-northeast-1',
            'version' => '2006-03-01',
            'credentials' => ['key' => '', 'secret' => ''],
            'handler' => $mock,
        ]);
    }
}