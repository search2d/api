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
            $this->fail(json_last_error_msg());
        }

        return $data;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     * @param mixed|null $data
     */
    protected function assertSuccessResponse(ResponseInterface $response, int $status, $data = null): void
    {
        $this->assertSame($status, $response->getStatusCode());

        $body = $this->decodeBody($response);

        if ($data !== null) {
            $this->assertEquals($data, $body);
        }
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

        $body = $this->decodeBody($response);

        $this->assertObjectHasAttribute('error', $body);
        $this->assertObjectHasAttribute('message', $body->error);

        if ($message !== null) {
            $this->assertSame($body->error->message, $message);
        }
    }
}