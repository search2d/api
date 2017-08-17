<?php
declare(strict_types=1);

namespace Search2d\Test;

use Faker\Provider\Base;

class FakerProvider extends Base
{
    /**
     * @return string
     */
    public function mimeImage(): string
    {
        $mimes = [
            'image/png',
            'image/gif',
            'image/jpeg',
            'image/tiff',
        ];
        return $mimes[array_rand($mimes)];
    }
}