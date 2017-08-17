<?php
declare(strict_types=1);

namespace Search2d\Test\Domain\Search;

use Search2d\Domain\Search\Sha1;
use Search2d\Domain\Search\Sha1ValidationException;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Domain\Search\Sha1
 */
class Sha1Test extends TestCase
{
    /**
     * @return void
     */
    public function testCreate(): void
    {
        $hash = Sha1::create('foo');
        $this->assertSame('0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33', $hash->value);
    }

    /**
     * @param string $value
     * @return void
     * @dataProvider providerValidationOk
     */
    public function testValidationOk(string $value): void
    {
        $hash = new Sha1($value);
        $this->assertSame($value, $hash->value);
    }

    /**
     * @return array
     */
    public function providerValidationOk(): array
    {
        return [
            ['0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33'], // 英数40文字（小文字）
            ['0BEEC7B5EA3F0FDBC95D0DD47F3C5BC275DA8A33'], // 英数40文字（大文字）
        ];
    }

    /**
     * @param string $value
     * @return void
     * @dataProvider providerValidationNg
     */
    public function testValidationNg(string $value): void
    {
        $this->expectException(Sha1ValidationException::class);
        new Sha1($value);
    }

    /**
     * @return array
     */
    public function providerValidationNg(): array
    {
        return [
            ['****************************************'],  // 英数以外40文字
            ['0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a3'],   // 英数39文字
            ['0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a333'], // 英数41文字
            [''],                                          // 空文字列
        ];
    }
}