<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Connection;
use Search2d\Domain\Search\Detail;
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
  `work_url`,
  `work_title`,
  `work_caption`,
  `work_created`,
  `author_url`,
  `author_name`,
  `author_biog`
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
            new Detail(
                $data['work_url'],
                $data['work_title'],
                $data['work_caption'],
                new Chronos($data['work_created'], 'UTC'),
                $data['author_url'],
                $data['author_name'],
                $data['author_biog']
            )
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
  `work_url`,
  `work_title`,
  `work_caption`,
  `work_created`,
  `author_url`,
  `author_name`,
  `author_biog`
) VALUES (
  :sha1,
  :mime,
  :size,
  :width,
  :height,
  :work_url,
  :work_title,
  :work_caption,
  :work_created,
  :author_url,
  :author_name,
  :author_biog
) ON DUPLICATE KEY UPDATE
  `mime` = VALUES(`mime`),
  `size` = VALUES(`size`),
  `width` = VALUES(`width`),
  `height` = VALUES(`height`),
  `work_url` = VALUES(`work_url`),
  `work_title` = VALUES(`work_title`),
  `work_caption` = VALUES(`work_caption`),
  `work_created` = VALUES(`work_created`),
  `author_url` = VALUES(`author_url`),
  `author_name` = VALUES(`author_name`),
  `author_biog` = VALUES(`author_biog`)
EOQ;
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('sha1', $indexedImage->getSha1(), \PDO::PARAM_STR);
        $stmt->bindValue('mime', $indexedImage->getMime(), \PDO::PARAM_STR);
        $stmt->bindValue('size', $indexedImage->getSize(), \PDO::PARAM_INT);
        $stmt->bindValue('width', $indexedImage->getWidth(), \PDO::PARAM_INT);
        $stmt->bindValue('height', $indexedImage->getHeight(), \PDO::PARAM_INT);
        $stmt->bindValue('work_url', $indexedImage->getDetail()->getWorkUrl(), \PDO::PARAM_STR);
        $stmt->bindValue('work_title', $indexedImage->getDetail()->getWorkTitle(), \PDO::PARAM_STR);
        $stmt->bindValue('work_caption', $indexedImage->getDetail()->getWorkCaption(), \PDO::PARAM_STR);
        $stmt->bindValue('work_created', $indexedImage->getDetail()->getWorkCreated(), \PDO::PARAM_STR);
        $stmt->bindValue('author_url', $indexedImage->getDetail()->getAuthorUrl(), \PDO::PARAM_STR);
        $stmt->bindValue('author_name', $indexedImage->getDetail()->getAuthorName(), \PDO::PARAM_STR);
        $stmt->bindValue('author_biog', $indexedImage->getDetail()->getAuthorBiog(), \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new \RuntimeException();
        }
    }
}