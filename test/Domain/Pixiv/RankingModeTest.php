<?php
declare(strict_types=1);

namespace Search2d\Test\Domain\Pixiv;

use Search2d\Domain\Pixiv\RankingMode;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Domain\Pixiv\RankingMode
 */
class RankingModeTest extends TestCase
{
    /**
     * @param string $value
     * @return void
     * @dataProvider providerValidationOk
     */
    public function testValidationOk(string $value): void
    {
        $mode = new RankingMode($value);
        $this->assertSame($value, $mode->value);
    }

    /**
     * @return array
     */
    public function providerValidationOk(): array
    {
        return [
            ['day'],
            ['day_male'],
            ['day_female'],
            ['day_r18'],
            ['day_male_r18'],
            ['day_female_r18'],
            ['week'],
            ['week_original'],
            ['week_rookie'],
            ['week_r18'],
            ['week_r18g'],
            ['month'],
        ];
    }

    /**
     * @param string $value
     * @return void
     * @dataProvider providerValidationNg
     */
    public function testValidationNg(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new RankingMode($value);
    }

    /**
     * @return array
     */
    public function providerValidationNg(): array
    {
        return [
            ['invalid'],
        ];
    }
}