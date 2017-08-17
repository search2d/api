<?php
declare(strict_types=1);

namespace Search2d\Domain\Search;

interface ImageFetcher
{
    /**
     * @param string $url
     * @return \Search2d\Domain\Search\Image
     * @throws \Search2d\Domain\Search\UrlValidationException
     * @throws \Search2d\Domain\Search\ImageValidationException
     */
    public function fetch(string $url): Image;
}