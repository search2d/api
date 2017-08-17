<?php
declare(strict_types=1);

namespace Search2d\Presentation\Api;

use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class Helper
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @param mixed $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function responseSuccess(ResponseInterface $response, int $status, $data): ResponseInterface
    {
        $json = json_encode($data);
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        return $response
            ->withBody(stream_for($json))
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @param string $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function responseFailure(ResponseInterface $response, int $status, string $message): ResponseInterface
    {
        $json = json_encode(['error' => ['message' => $message]]);
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        return $response
            ->withBody(stream_for($json))
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json;charset=utf-8');
    }
}