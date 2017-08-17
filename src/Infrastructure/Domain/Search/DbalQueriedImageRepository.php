<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use Doctrine\DBAL\Connection;
use Search2d\Domain\Search\Mime;
use Search2d\Domain\Search\QueriedImage;
use Search2d\Domain\Search\QueriedImageRepository;
use Search2d\Domain\Search\Sha1;

class DbalQueriedImageRepository implements QueriedImageRepository
{
    /** @var \Doctrine\DBAL\Connection */
    private $conn;

    /**
     * @param \Doctrine\DBAL\Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return null|\Search2d\Domain\Search\QueriedImage
     */
    public function find(Sha1 $sha1): ?QueriedImage
    {
        $sql = <<<EOQ
SELECT
  `sha1`,
  `mime`,
  `size`,
  `width`,
  `height`
FROM
  `queried_images`
WHERE
  `sha1` = :sha1
EOQ;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('sha1', $sha1, \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new \RuntimeException();
        }

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        return new QueriedImage(
            new Sha1($data['sha1']),
            new Mime($data['mime']),
            (int)$data['size'],
            (int)$data['width'],
            (int)$data['height']
        );
    }

    /**
     * @param \Search2d\Domain\Search\QueriedImage $queriedImage
     * @return void
     */
    public function save(QueriedImage $queriedImage): void
    {
        $sql = <<<EOQ
INSERT INTO `queried_images` (
  `sha1`,
  `mime`,
  `size`,
  `width`,
  `height`
) VALUES (
  :sha1,
  :mime,
  :size,
  :width,
  :height
) ON DUPLICATE KEY UPDATE
  `mime` = VALUES(`mime`),
  `size` = VALUES(`size`),
  `width` = VALUES(`width`),
  `height` = VALUES(`height`)
EOQ;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('sha1', $queriedImage->getSha1(), \PDO::PARAM_STR);
        $stmt->bindValue('mime', $queriedImage->getMime(), \PDO::PARAM_STR);
        $stmt->bindValue('size', $queriedImage->getSize(), \PDO::PARAM_INT);
        $stmt->bindValue('width', $queriedImage->getWidth(), \PDO::PARAM_INT);
        $stmt->bindValue('height', $queriedImage->getHeight(), \PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new \RuntimeException();
        }
    }
}