<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

class IpResolver
{
    /**
     * @param string $host
     * @return array|false
     */
    public function resolve(string $host)
    {
        return gethostbynamel($host);
    }
}