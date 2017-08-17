<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

use Cake\Chronos\ChronosInterface;

class Detail
{
    /** @var string */
    private $workUrl;

    /** @var string */
    private $workTitle;

    /** @var string */
    private $workCaption;

    /** @var \Cake\Chronos\ChronosInterface */
    private $workCreated;

    /** @var string */
    private $authorUrl;

    /** @var string */
    private $authorName;

    /** @var string */
    private $authorBiog;

    /**
     * @param string $workUrl
     * @param string $workTitle
     * @param string $workCaption
     * @param \Cake\Chronos\ChronosInterface $workCreated
     * @param string $authorUrl
     * @param string $authorName
     * @param string $authorBiog
     */
    public function __construct(
        string $workUrl,
        string $workTitle,
        string $workCaption,
        ChronosInterface $workCreated,
        string $authorUrl,
        string $authorName,
        string $authorBiog
    )
    {
        $this->workUrl = $workUrl;
        $this->workTitle = $workTitle;
        $this->workCaption = $workCaption;
        $this->workCreated = $workCreated;
        $this->authorUrl = $authorUrl;
        $this->authorName = $authorName;
        $this->authorBiog = $authorBiog;
    }

    /**
     * @return string
     */
    public function getWorkUrl(): string
    {
        return $this->workUrl;
    }

    /**
     * @return string
     */
    public function getWorkTitle(): string
    {
        return $this->workTitle;
    }

    /**
     * @return string
     */
    public function getWorkCaption(): string
    {
        return $this->workCaption;
    }

    /**
     * @return \Cake\Chronos\ChronosInterface
     */
    public function getWorkCreated(): ChronosInterface
    {
        return $this->workCreated;
    }

    /**
     * @return string
     */
    public function getAuthorUrl(): string
    {
        return $this->authorUrl;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * @return string
     */
    public function getAuthorBiog(): string
    {
        return $this->authorBiog;
    }
}