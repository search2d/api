<?php
declare(strict_types=1);

namespace Search2d\Test\Domain\Pixiv;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cake\Chronos\Date;
use Search2d\Domain\Pixiv\RankingDate;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Domain\Pixiv\RankingDate
 */
class RankingDateTest extends TestCase
{
    /**
     * @param \Cake\Chronos\ChronosInterface $now
     * @param \Cake\Chronos\Date $expectedDate
     * @return void
     * @dataProvider providerLatest
     */
    public function testLatest(ChronosInterface $now, Date $expectedDate): void
    {
        $this->assertTrue(RankingDate::latest($now)->value->eq($expectedDate));
    }

    /**
     * @return array
     */
    public function providerLatest(): array
    {
        return [
            [new Chronos('2017-07-10 11:59:59'), new Date('2017-07-08')],
            [new Chronos('2017-07-10 12:00:00'), new Date('2017-07-09')],
            [new Chronos('2017-07-10 12:00:01'), new Date('2017-07-09')],
        ];
    }
}