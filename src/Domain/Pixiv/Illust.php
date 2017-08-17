<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

use Cake\Chronos\ChronosInterface;

class Illust
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $caption;

    /** @var \Cake\Chronos\ChronosInterface */
    private $created;

    /** @var \Search2d\Domain\Pixiv\IllustPageCollection */
    private $pages;

    /** @var int */
    private $userId;

    /** @var string */
    private $userName;

    /** @var string */
    private $userBiog;

    /**
     * @param int $id
     * @param string $title
     * @param string $caption
     * @param \Cake\Chronos\ChronosInterface $created
     * @param \Search2d\Domain\Pixiv\IllustPageCollection $pages
     * @param int $userId
     * @param string $userName
     * @param string $userBiog
     */
    public function __construct(
        int $id,
        string $title,
        string $caption,
        ChronosInterface $created,
        IllustPageCollection $pages,
        int $userId,
        string $userName,
        string $userBiog
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->pages = $pages;
        $this->caption = $caption;
        $this->created = $created;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userBiog = $userBiog;
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
        return sprintf('https://www.pixiv.net/member_illust.php?mode=medium&illust_id=%d', $this->id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @return \Cake\Chronos\ChronosInterface
     */
    public function getCreated(): ChronosInterface
    {
        return $this->created;
    }

    /**
     * @return \Search2d\Domain\Pixiv\IllustPageCollection
     */
    public function getPages(): IllustPageCollection
    {
        return $this->pages;
    }

    /**
     * @return string
     */
    public function getUserUrl(): string
    {
        return sprintf('https://www.pixiv.net/member.php?id=%d', $this->userId);
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getUserBiog(): string
    {
        return $this->userBiog;
    }
}