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
    /**
     * @return void
     */
    public function testHttpScheme(): void
    {
        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve('search2d.net')->shouldBeCalled()->willReturn(['203.0.113.1']); // RFC5737で規定される例示用IP

        $urlValidator = new UrlValidator($resolver->reveal());

        try {
            $urlValidator->validate('http://search2d.net/logo.png');
        } catch (UrlValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testHttpsScheme(): void
    {
        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve('search2d.net')->shouldBeCalled()->willReturn(['203.0.113.1']); // RFC5737で規定される例示用IP

        $urlValidator = new UrlValidator($resolver->reveal());

        try {
            $urlValidator->validate('https://search2d.net/logo.png');
        } catch (UrlValidationException $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testFtpSchemeThrowsException(): void
    {
        $this->expectException(UrlValidationException::class);

        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve(Argument::any())->shouldNotBeCalled();

        $urlValidator = new UrlValidator($resolver->reveal());
        $urlValidator->validate('ftp://search2d.net/logo.png');
    }

    /**
     * @return void
     */
    public function testPrivateNetworkThrowsException(): void
    {
        $this->expectException(UrlValidationException::class);

        $resolver = $this->prophesize(IpResolver::class);
        $resolver->resolve('localhost')->shouldBeCalled()->willReturn(['127.0.0.1']);

        $urlValidator = new UrlValidator($resolver->reveal());
        $urlValidator->validate('http://localhost/logo.png');
    }
}