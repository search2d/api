<?php
declare(strict_types=1);

namespace Search2d\Test\Infrastructure\Domain\Search;

use Prophecy\Argument;
use Search2d\Domain\Search\UrlValidationException;
use Search2d\Infrastructure\Domain\Search\IpResolver;
use Search2d\Infrastructure\Domain\Search\UrlValidator;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Infrastructure\Domain\Search\UrlValidator
 */
class UrlValidatorTest extends TestCase
{
    // 例示用IP（RFC5737）
    private const EXAMPLE_IP = '203.0.113.1';

    /**
     * @param string $url
     * @return void
     * @dataProvider providerSchemeOk
     */
    public function testSchemeOk(string $url): void
    {
        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve(Argument::any())->shouldBeCalled()->willReturn([self::EXAMPLE_IP]);

        $urlValidator = new UrlValidator($resolver->reveal());

        try {
            $urlValidator->validate($url);
        } catch (UrlValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function providerSchemeOk(): array
    {
        return [
            ['http://search2d.net/example.png'],
            ['https://search2d.net/example.png'],
        ];
    }

    /**
     * @param string $url
     * @return void
     * @dataProvider providerSchemeNg
     */
    public function testSchemeNg(string $url): void
    {
        $this->expectException(UrlValidationException::class);

        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve(Argument::any())->shouldNotBeCalled();

        $urlValidator = new UrlValidator($resolver->reveal());
        $urlValidator->validate($url);
    }

    /**
     * @return array
     */
    public function providerSchemeNg(): array
    {
        return [
            ['ftp://search2d.net/example.png'],
        ];
    }

    /**
     * @param string[] $addrs
     * @return void
     * @dataProvider providerAddressNg
     */
    public function testAddressNg(array $addrs): void
    {
        $this->expectException(UrlValidationException::class);

        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve(Argument::any())->shouldBeCalled()->willReturn($addrs);

        $urlValidator = new UrlValidator($resolver->reveal());
        $urlValidator->validate('http://search2d.net/example.png');
    }

    /**
     * @return string[][]
     */
    public function providerAddressNg(): array
    {
        return [
            [['10.0.0.1']],
            [['10.255.255.255']],
            [['172.16.0.1']],
            [['172.31.255.255']],
            [['192.168.0.1']],
            [['192.168.255.255']],
            [['127.0.0.1']],
            [['127.255.255.255']],
            [['169.254.0.1']],
            [['169.254.255.255']],
        ];
    }
}