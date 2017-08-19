<?php
declare(strict_types=1);

namespace Search2d\Test\Presentation\Api\Action;

use Psr\Http\Message\ResponseInterface;

trait HelperTrait
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return object
     */
    protected function decodeBody(ResponseInterface $response)
    {
        $this->assertSame('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);

        $data = json_decode($response->getBody()->getContents());
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        return $data;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @param null|string $message
     * @return void
     */
    protected function assertFailureResponse(ResponseInterface $response, int $status, ?string $message = null): void
    {
        $this->assertSame($status, $response->getStatusCode());

        $data = $this->decodeBody($response);

        $this->assertObjectHasAttribute('error', $data);
        $this->assertObjectHasAttribute('message', $data->error);

        if ($message !== null) {
            $this->assertSame($data->error->message, $message);
        }
    }
}