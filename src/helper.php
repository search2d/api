<?php
declare(strict_types=1);

/**
 * @param string $name
 * @param null|string $default
 * @return string
 */
function env_str(string $name, ?string $default = null): string
{
    $value = getenv($name);
    if ($value === false) {
        if ($default !== null) {
            return $default;
        } else {
            throw new \LogicException(sprintf('environment variable "%s" is missing', $name));
        }
    }

    return $value;
}

/**
 * @param string $name
 * @param int|null $default
 * @return int
 */
function env_int(string $name, ?int $default = null): int
{
    $value = getenv($name);
    if ($value === false) {
        if ($default !== null) {
            return $default;
        } else {
            throw new \LogicException(sprintf('environment variable "%s" is missing', $name));
        }
    }

    return (int)$value;
}

/**
 * @param string $name
 * @param bool|null $default
 * @return bool
 */
function env_bool(string $name, ?bool $default = null): bool
{
    $value = getenv($name);
    if ($value === false) {
        if ($default !== null) {
            return $default;
        } else {
            throw new \LogicException(sprintf('environment variable "%s" is missing', $name));
        }
    }

    return (bool)$value;
}

/**
 * @param string $name
 * @param float|null $default
 * @return float
 */
function env_float(string $name, ?float $default = null): float
{
    $value = getenv($name);
    if ($value === false) {
        if ($default !== null) {
            return $default;
        } else {
            throw new \LogicException(sprintf('environment variable "%s" is missing', $name));
        }
    }

    return (float)$value;
}