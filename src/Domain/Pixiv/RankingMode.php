<?php
declare(strict_types=1);

namespace Search2d\Domain\Pixiv;

/**
 * @property-read string $value
 */
class RankingMode
{
    const MODE_DAY = "day";
    const MODE_DAY_MALE = "day_male";
    const MODE_DAY_FEMALE = "day_female";
    const MODE_DAY_R18 = "day_r18";
    const MODE_DAY_MALE_R18 = "day_male_r18";
    const MODE_DAY_FEMALE_R18 = "day_female_r18";
    const MODE_WEEK = "week";
    const MODE_WEEK_ORIGINAL = "week_original";
    const MODE_WEEK_ROOKIE = "week_rookie";
    const MODE_WEEK_R18 = "week_r18";
    const MODE_WEEK_R18G = "week_r18g";
    const MODE_MONTH = "month";

    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!$this->validateMode($value)) {
            throw new \InvalidArgumentException();
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function validateMode(string $value): bool
    {
        return in_array(
            $value,
            [
                self::MODE_DAY,
                self::MODE_DAY_MALE,
                self::MODE_DAY_FEMALE,
                self::MODE_DAY_R18,
                self::MODE_DAY_MALE_R18,
                self::MODE_DAY_FEMALE_R18,
                self::MODE_WEEK,
                self::MODE_WEEK_ORIGINAL,
                self::MODE_WEEK_ROOKIE,
                self::MODE_WEEK_R18,
                self::MODE_WEEK_R18G,
                self::MODE_MONTH,
            ]
        );
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