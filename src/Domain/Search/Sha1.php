<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

/**
 * @property-read string $value
 */
class Sha1
{
    /** @var string */
    private $value;

    /**
     * @param string $data
     * @return \Search2d\Domain\Search\Sha1
     */
    public static function create(string $data): self
    {
        return new self(sha1($data));
    }

    /**
     * @param string $value
     * @throws \Search2d\Domain\Search\Sha1ValidationException
     */
    public function __construct(string $value)
    {
        if (!preg_match('/^[a-zA-Z0-9]{40}$/', $value)) {
            throw new Sha1ValidationException();
        }

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