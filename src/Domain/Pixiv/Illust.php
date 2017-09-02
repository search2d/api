<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

use Cake\Chronos\ChronosInterface;

class Illust
{
    /** @var int */
    private $id;

    /** @var string */
    private $url;

    /** @var string */
    private $title;

    /** @var \Search2d\Domain\Pixiv\IllustPageCollection */
    private $pages;

    /** @var \Cake\Chronos\ChronosInterface */
    private $crawledAt;

    /**
     * @param int $id
     * @param string $url
     * @param string $title
     * @param \Search2d\Domain\Pixiv\IllustPageCollection $pages
     * @param \Cake\Chronos\ChronosInterface $crawledAt
     */
    public function __construct(int $id, string $url, string $title, IllustPageCollection $pages, ChronosInterface $crawledAt)
    {
        $this->id = $id;
        $this->url = $url;
        $this->title = $title;
        $this->pages = $pages;
        $this->crawledAt = $crawledAt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return \Search2d\Domain\Pixiv\IllustPageCollection
     */
    public function getPages(): IllustPageCollection
    {
        return $this->pages;
    }

    /**
     * @return \Cake\Chronos\ChronosInterface
     */
    public function getCrawledAt(): ChronosInterface
    {
        return $this->crawledAt;
    }
}