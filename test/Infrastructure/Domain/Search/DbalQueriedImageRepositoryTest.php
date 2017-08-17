<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use Search2d\Domain\Search\Mime;
use Search2d\Domain\Search\QueriedImage;
use Search2d\Domain\Search\Sha1;
use Search2d\Infrastructure\Domain\Search\DbalQueriedImageRepository;
use Search2d\Test\TestCase;

/**
 * @group database
 * @covers \Search2d\Infrastructure\Domain\Search\DbalQueriedImageRepository
 */
class DbalQueriedImageRepositoryTest extends TestCase
{
    /** @var \Search2d\Infrastructure\Domain\Search\DbalQueriedImageRepository */
    private $repository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->repository = new DbalQueriedImageRepository($this->connection());
    }

    /**
     * @return void
     */
    public function testFind(): void
    {
        $faker = $this->faker();

        $sha1 = $faker->sha1;
        $mime = $faker->mimeImage;
        $size = $faker->randomNumber();
        $width = $faker->randomNumber();
        $height = $faker->randomNumber();

        $qb = $this->queryBuilder();

        $values = [
            'sha1' => $qb->createPositionalParameter($sha1, \PDO::PARAM_STR),
            'mime' => $qb->createPositionalParameter($mime, \PDO::PARAM_STR),
            'size' => $qb->createPositionalParameter($size, \PDO::PARAM_INT),
            'width' => $qb->createPositionalParameter($width, \PDO::PARAM_INT),
            'height' => $qb->createPositionalParameter($height, \PDO::PARAM_INT),
        ];

        $qb->insert('queried_images')->values($values)->execute();

        $queriedImage = $this->repository->find(new Sha1($sha1));

        $this->assertNotNull($queriedImage);
        $this->assertSame($sha1, $queriedImage->getSha1()->value);
        $this->assertSame($mime, $queriedImage->getMime()->value);
        $this->assertSame($size, $queriedImage->getSize());
        $this->assertSame($width, $queriedImage->getWidth());
        $this->assertSame($height, $queriedImage->getHeight());
    }

    /**
     * @return void
     */
    public function testFindNotFound(): void
    {
        $faker = $this->faker();

        $queriedImage = $this->repository->find(new Sha1($faker->sha1));

        $this->assertNull($queriedImage);
    }

    /**
     * @return void
     */
    public function testSaveInsert(): void
    {
        $faker = $this->faker();

        $queriedImage = new QueriedImage(
            new Sha1($faker->sha1),
            new Mime($faker->mimeImage),
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->randomNumber()
        );

        $this->repository->save($queriedImage);

        $qb = $this->queryBuilder();

        $rows = $qb->select('*')
            ->from('queried_images')
            ->where('sha1 = ?')
            ->setParameter(0, $queriedImage->getSha1(), \PDO::PARAM_STR)
            ->execute()
            ->fetchAll();

        $this->assertCount(1, $rows);
        $this->assertArraySubset([
            'mime' => $queriedImage->getMime(),
            'size' => $queriedImage->getSize(),
            'width' => $queriedImage->getWidth(),
            'height' => $queriedImage->getHeight(),
        ], $rows[0]);
    }

    /**
     * @return void
     */
    public function testSaveUpdate(): void
    {
        $faker = $this->faker();

        $sha1 = $faker->sha1;

        $qb = $this->queryBuilder();
        $qb->insert('queried_images')
            ->values([
                'sha1' => $qb->createPositionalParameter($sha1, \PDO::PARAM_STR),
                'mime' => $qb->createPositionalParameter($faker->unique()->mimeImage, \PDO::PARAM_STR),
                'size' => $qb->createPositionalParameter($faker->unique()->randomNumber(), \PDO::PARAM_INT),
                'width' => $qb->createPositionalParameter($faker->unique()->randomNumber(), \PDO::PARAM_INT),
                'height' => $qb->createPositionalParameter($faker->unique()->randomNumber(), \PDO::PARAM_INT),
            ])
            ->execute();

        $queriedImage = new QueriedImage(
            new Sha1($sha1),
            new Mime($faker->unique()->mimeImage),
            $faker->unique()->randomNumber(),
            $faker->unique()->randomNumber(),
            $faker->unique()->randomNumber()
        );
        $this->repository->save($queriedImage);

        $qb = $this->queryBuilder();
        $rows = $qb->select('*')
            ->from('queried_images')
            ->where('sha1 = ?')
            ->setParameter(0, $sha1, \PDO::PARAM_STR)
            ->execute()
            ->fetchAll();

        $this->assertCount(1, $rows);
        $this->assertArraySubset([
            'mime' => $queriedImage->getMime(),
            'size' => $queriedImage->getSize(),
            'width' => $queriedImage->getWidth(),
            'height' => $queriedImage->getHeight(),
        ], $rows[0]);
    }
}