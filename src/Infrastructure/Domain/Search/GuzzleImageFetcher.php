<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\ImageFetcher;

class GuzzleImageFetcher implements ImageFetcher
{
    /** @var \GuzzleHttp\ClientInterface */
    private $client;

    /** @var \Search2d\Infrastructure\Domain\Search\UrlValidator */
    private $urlValidator;

    /**
     * @param \GuzzleHttp\ClientInterface $client
     * @param \Search2d\Infrastructure\Domain\Search\UrlValidator $urlValidator
     */
    public function __construct(ClientInterface $client, UrlValidator $urlValidator)
    {
        $this->client = $client;
        $this->urlValidator = $urlValidator;
    }

    /**
     * @param string $url
     * @return \Search2d\Domain\Search\Image
     * @throws \Search2d\Domain\Search\UrlValidationException
     * @throws \Search2d\Domain\Search\ImageValidationException
     */
    public function fetch(string $url): Image
    {
        $this->urlValidator->validate($url);

        $response = $this->client->request('GET', $url, [
            RequestOptions::HTTP_ERRORS => true,
        ]);

        return Image::create($response->getBody()->getContents());
    }
}