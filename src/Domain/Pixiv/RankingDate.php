<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

use Cake\Chronos\ChronosInterface;
use Cake\Chronos\Date;

/**
 * @property-read \Cake\Chronos\Date $value
 */
class RankingDate
{
    /** @var \Cake\Chronos\Date */
    private $value;

    /**
     * @param \Cake\Chronos\ChronosInterface $now
     * @return \Search2d\Domain\Pixiv\RankingDate
     */
    public static function latest(ChronosInterface $now = null): self
    {
        $now = $now ?? Date::now();

        // 毎日12時に前日のランキングが公開される
        if ($now->gte($now->hour(12)->minute(0)->second(0))) {
            return new self(Date::createFromTimestamp($now->subDays(1)->getTimestamp()));
        } else {
            return new self(Date::createFromTimestamp($now->subDays(2)->getTimestamp()));
        }
    }

    /**
     * @param \Cake\Chronos\Date $value
     */
    public function __construct(Date $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name !== 'value') {
            throw new \LogicException();
        }

        return $this->value;
    }
}