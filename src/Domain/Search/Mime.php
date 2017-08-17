<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

/**
 * @property-read string $value
 */
class Mime
{
    /** @var string */
    private $value;

    /**
     * @param string $data
     * @return \Search2d\Domain\Search\Mime
     * @throws \Search2d\Domain\Search\MimeDetectionException
     */
    public static function detect(string $data): self
    {
        if (strlen($data) >= 2 && bin2hex($data[0]) === 'ff' && bin2hex($data[1]) === 'd8') {
            return new self('image/jpeg');
        }

        if (strlen($data) >= 4 && bin2hex($data[0]) === '89' && bin2hex($data[1]) === '50' && bin2hex($data[2]) === '4e' && bin2hex($data[3]) === '47') {
            return new self('image/png');
        }

        if (strlen($data) >= 4 && bin2hex($data[0]) === '47' && bin2hex($data[1]) === '49' && bin2hex($data[2]) === '46' && bin2hex($data[3]) === '38') {
            return new self('image/gif');
        }

        throw new MimeDetectionException();
    }

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if ($name !== 'value') {
            throw new \LogicException();
        }

        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}