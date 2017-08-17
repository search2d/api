<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use Search2d\Infrastructure\Domain\Search\IpResolver;
use Search2d\Test\TestCase;

/**
 * @group outbound
 * @covers \Search2d\Infrastructure\Domain\Search\IpResolver
 */
class IpResolverTest extends TestCase
{
    const IPV4_PATTERN = '/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';

    /**
     * @return void
     */
    public function testResolveValidDomain(): void
    {
        $resolver = new IpResolver();
        $ips = $resolver->resolve('www.google.com');
        $this->assertNotFalse($ips);
        foreach ($ips as $ip) {
            $this->assertRegExp(self::IPV4_PATTERN, $ip);
        }
    }

    /**
     * @return void
     */
    public function testResolveInvalidDomain(): void
    {
        $resolver = new IpResolver();
        $ips = $resolver->resolve('foo.invalid'); // 例示用の無効なドメイン（RFC2606）
        $this->assertFalse($ips);
    }

    /**
     * @return void
     */
    public function testResolveIpAddress(): void
    {
        $resolver = new IpResolver();
        $ips = $resolver->resolve('127.0.0.1');
        $this->assertSame(['127.0.0.1'], $ips);
    }
}