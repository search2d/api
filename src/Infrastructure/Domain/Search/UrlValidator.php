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
            $this->validateNetwork($ip);
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
     * @param string $addr
     * @throws \Search2d\Domain\Search\UrlValidationException
     */
    private function validateNetwork(string $addr): void
    {
        $deniedNetworks = [
            '10.0.0.0/8',    // プライベートアドレス（クラスA）
            '172.16.0.0/12',  // プライベートアドレス（クラスB）
            '192.168.0.0/16', // プライベートアドレス（クラスC）
            '127.0.0.0/8',   // ローカルループバックアドレス
            '169.254.0.0/16', // リンクローカルアドレス
        ];

        foreach ($deniedNetworks as $deniedNetwork) {
            if ($this->inNetwork($addr, $deniedNetwork)) {
                throw new UrlValidationException();
            }
        }
    }

    /**
     * @param string $addr
     * @param string $network
     * @return bool
     */
    private function inNetwork($addr, $network): bool
    {
        list($networkAddr, $networkMask) = explode('/', $network);

        $shift = 32 - $networkMask;

        return (ip2long($addr) >> $shift << $shift) === (ip2long($networkAddr) >> $shift << $shift);
    }
}