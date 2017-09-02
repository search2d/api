<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Connection;
use Search2d\Domain\Search\IndexedImage;
use Search2d\Domain\Search\IndexedImageRepository;
use Search2d\Domain\Search\Mime;
use Search2d\Domain\Search\Sha1;

class DbalIndexedImageRepository implements IndexedImageRepository
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
     * @return null|\Search2d\Domain\Search\IndexedImage
     */
    public function find(Sha1 $sha1): ?IndexedImage
    {
        $sql = <<<EOQ
SELECT
  `sha1`,
  `mime`,
  `size`,
  `width`,
  `height`,
  `image_url`,
  `page_url`,
  `page_title`,
  `crawled_at`
FROM
  `indexed_images`
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

        return new IndexedImage(
            new Sha1($data['sha1']),
            new Mime($data['mime']),
            (int)$data['size'],
            (int)$data['width'],
            (int)$data['height'],
            $data['image_url'],
            $data['page_url'],
            $data['page_title'],
            new Chronos($data['crawled_at'], 'UTC')
        );
    }

    /**
     * @param \Search2d\Domain\Search\IndexedImage $indexedImage
     * @return void
     */
    public function save(IndexedImage $indexedImage): void
    {
        $sql = <<<EOQ
INSERT INTO `indexed_images` (
  `sha1`,
  `mime`,
  `size`,
  `width`,
  `height`,
  `image_url`,
  `page_url`,
  `page_title`,
  `crawled_at`
) VALUES (
  :sha1,
  :mime,
  :size,
  :width,
  :height,
  :image_url,
  :page_url,
  :page_title,
  :crawled_at
) ON DUPLICATE KEY UPDATE
  `mime` = VALUES(`mime`),
  `size` = VALUES(`size`),
  `width` = VALUES(`width`),
  `height` = VALUES(`height`),
  `image_url` = VALUES(`image_url`),
  `page_url` = VALUES(`page_url`),
  `page_title` = VALUES(`page_title`),
  `crawled_at` = VALUES(`crawled_at`)
EOQ;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('sha1', $indexedImage->getSha1(), \PDO::PARAM_STR);
        $stmt->bindValue('mime', $indexedImage->getMime(), \PDO::PARAM_STR);
        $stmt->bindValue('size', $indexedImage->getSize(), \PDO::PARAM_INT);
        $stmt->bindValue('width', $indexedImage->getWidth(), \PDO::PARAM_INT);
        $stmt->bindValue('height', $indexedImage->getHeight(), \PDO::PARAM_INT);
        $stmt->bindValue('image_url', $indexedImage->getImageUrl(), \PDO::PARAM_STR);
        $stmt->bindValue('page_url', $indexedImage->getPageUrl(), \PDO::PARAM_STR);
        $stmt->bindValue('page_title', $indexedImage->getPageTitle(), \PDO::PARAM_STR);
        $stmt->bindValue('crawled_at', $indexedImage->getCrawledAt(), \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new \RuntimeException();
        }
    }
}