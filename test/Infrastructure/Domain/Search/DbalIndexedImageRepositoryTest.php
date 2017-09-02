<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use Cake\Chronos\Chronos;
use Search2d\Domain\Search\IndexedImage;
use Search2d\Domain\Search\Mime;
use Search2d\Domain\Search\Sha1;
use Search2d\Infrastructure\Domain\Search\DbalIndexedImageRepository;
use Search2d\Test\TestCase;

/**
 * @group database
 * @covers \Search2d\Infrastructure\Domain\Search\DbalIndexedImageRepository
 */
class DbalIndexedImageRepositoryTest extends TestCase
{
    /** @var \Search2d\Infrastructure\Domain\Search\DbalIndexedImageRepository */
    private $repository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->repository = new DbalIndexedImageRepository($this->connection());
    }

    /**
     * @return void
     */
    public function testFind(): void
    {
        $faker = $this->faker();

        $fake = new IndexedImage(
            new Sha1($faker->sha1),
            new Mime($faker->mimeImage),
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->url,
            $faker->url,
            $faker->text(32),
            new Chronos('2017-01-01 00:00:00', 'UTC')
        );

        $this->repository->save($fake);

        $indexedImage = $this->repository->find($fake->getSha1());

        $this->assertNotNull($indexedImage);
        $this->assertEquals($fake, $indexedImage);
    }
}