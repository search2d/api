<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Search;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Search2d\Domain\Search\NnsPoint;
use Search2d\Domain\Search\NnsPointCollection;
use Search2d\Domain\Search\NnsRepository;
use Search2d\Domain\Search\Sha1;

class GuzzleNnsRepository implements NnsRepository
{
    /** @var \GuzzleHttp\ClientInterface */
    private $client;

    /** @var \JsonMapper */
    private $jsonMapper;

    /**
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;

        $this->jsonMapper = new \JsonMapper();
        $this->jsonMapper->bExceptionOnMissingData = true;
        $this->jsonMapper->bExceptionOnUndefinedProperty = false;
    }

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @param int $radius
     * @param int $count
     * @return \Search2d\Domain\Search\NnsPointCollection
     */
    public function search(Sha1 $sha1, int $radius, int $count): NnsPointCollection
    {
        $response = $this->request('GET', '/search', [
            RequestOptions::QUERY => [
                'sha1' => $sha1->value,
                'radius' => $radius,
                'count' => $count,
            ],
        ]);

        /** @var \Search2d\Infrastructure\Domain\Search\NnsSearchResponse $result */
        $result = $this->decodeJson($response, new NnsSearchResponse());

        return new NnsPointCollection(array_map(function (NnsSearchResponsePoint $each) {
            return new NnsPoint(new Sha1($each->sha1), $each->dist);
        }, $result->points));
    }

    /**
     * @param \Search2d\Domain\Search\Sha1 $sha1
     * @return void
     */
    public function upsert(Sha1 $sha1): void
    {
        $this->request('POST', '/upsert', [
            RequestOptions::FORM_PARAMS => [
                'sha1' => $sha1->value,
            ],
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        assert(!isset($options[RequestOptions::HTTP_ERRORS]));
        $options[RequestOptions::HTTP_ERRORS] = true;

        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (ClientException $exception) {
            $error = $this->decodeJson($exception->getResponse(), new NnsErrorResponse());
            throw $exception;
        }

        return $response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $object
     * @return mixed
     */
    private function decodeJson(ResponseInterface $response, $object)
    {
        $contents = $response->getBody()->getContents();

        $data = json_decode($contents);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        try {
            return $this->jsonMapper->map($data, $object);
        } catch (\Exception $e) {
            throw new \RuntimeException('JSONデータのマッピングに失敗', 0, $e);
        }
    }
}