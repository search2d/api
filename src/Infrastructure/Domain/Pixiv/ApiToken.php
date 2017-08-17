<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

class ApiToken
{
    /** @var string */
    private $accessToken;

    /** @var string */
    private $refreshToken;

    /** @var \Cake\Chronos\ChronosInterface */
    private $createdAt;

    /** @var int */
    private $expiresIn;

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param int $expireIn
     * @return \Search2d\Infrastructure\Domain\Pixiv\ApiToken
     */
    public static function create(string $accessToken, string $refreshToken, int $expireIn)
    {
        return new self($accessToken, $refreshToken, Chronos::now(), $expireIn);
    }

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param \Cake\Chronos\ChronosInterface $createdAt
     * @param int $expiresIn
     */
    public function __construct(string $accessToken, string $refreshToken, ChronosInterface $createdAt, int $expiresIn)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->createdAt = $createdAt;
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return Chronos::now()->gte($this->createdAt->addSeconds($this->expiresIn));
    }
}