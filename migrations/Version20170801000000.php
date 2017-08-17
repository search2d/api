<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170801000000 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->createIndexedImagesTable();
        $this->createQueriedImagesTable();
    }

    /**
     * @return void
     */
    private function createIndexedImagesTable(): void
    {
        $query = <<<EOQ
CREATE TABLE `indexed_images` (
  `sha1`         VARCHAR(40) NOT NULL,
  `mime`         VARCHAR(64) NOT NULL,
  `size`         INT UNSIGNED NOT NULL,
  `width`        INT UNSIGNED NOT NULL,
  `height`       INT UNSIGNED NOT NULL,
  `work_url`     TEXT NOT NULL,
  `work_title`   VARCHAR(255) NOT NULL,
  `work_caption` TEXT NOT NULL,
  `work_created` DATETIME NOT NULL,
  `author_url`   TEXT NOT NULL,
  `author_name`  VARCHAR(255) NOT NULL,
  `author_biog`  TEXT NOT NULL,
  PRIMARY KEY (`sha1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
EOQ;
        $this->addSql($query);
    }

    /**
     * @return void
     */
    private function createQueriedImagesTable(): void
    {
        $query = <<<EOQ
CREATE TABLE `queried_images` (
  `sha1`   VARCHAR(40) NOT NULL,
  `mime`   VARCHAR(32) NOT NULL,
  `size`   INT UNSIGNED NOT NULL,
  `width`  INT UNSIGNED NOT NULL,
  `height` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`sha1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
EOQ;
        $this->addSql($query);
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        $this->dropIndexedImagesTable();
        $this->dropQueriedImagesTable();
    }

    /**
     * @return void
     */
    private function dropIndexedImagesTable(): void
    {
        $query = <<<EOQ
DROP TABLE `indexed_images`;
EOQ;
        $this->addSql($query);
    }

    /**
     * @return void
     */
    private function dropQueriedImagesTable(): void
    {
        $query = <<<EOQ
DROP TABLE `queried_images`;
EOQ;
        $this->addSql($query);
    }
}