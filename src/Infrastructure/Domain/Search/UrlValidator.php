<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use Search2d\Domain\Search\UrlValidationException;

class UrlValidator
{
    /** @var \Search2d\Infrastructure\Domain\Search\IpResolver */
    private $resolver;

    /**
     * @param \Search2d\Infrastructure\Domain\Search\IpResolver $resolver
     */
    public function __construct(IpResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $url
     * @throws \Search2d\Domain\Search\UrlValidationException
     */
    public function validate(string $url): void
    {
        [$scheme, $host] = $this->parseUrl($url);

        if (!in_array($scheme, ['http', 'https'])) {
            throw new UrlValidationException();
        }

        $ips = $this->resolver->resolve($host);
        if ($ips === false) {
            throw new \RuntimeException();
        }

        foreach ($ips as $ip) {
            $this->validateIp($ip);
        }
    }

    /**
     * @param string $url
     * @return array
     * @throws \Search2d\Domain\Search\UrlValidationException
     */
    private function parseUrl(string $url): array
    {
        $components = parse_url($url);

        if ($components === false) {
            throw new UrlValidationException();
        }

        if (!isset($components['scheme'], $components['host'])) {
            throw new UrlValidationException();
        }

        return [$components['scheme'], $components['host']];
    }

    /**
     * @param string $ip
     * @throws \Search2d\Domain\Search\UrlValidationException
     */
    private function validateIp(string $ip): void
    {
        $deniedAddrs = [
            '127.0.0.1'
        ];

        if (in_array($ip, $deniedAddrs)) {
            throw new UrlValidationException();
        }
    }
}