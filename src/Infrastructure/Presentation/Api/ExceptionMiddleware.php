<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Presentation\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Debug\Exception\FlattenException;
use Zend\Diactoros\Response;

class ExceptionMiddleware
{
    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        try {
            return $next($request, $response);
        } catch (\Exception $exception) {
            $json = json_encode(FlattenException::create($exception)->toArray());
            if ($json === false) {
                throw new \RuntimeException(json_last_error_msg(), json_last_error());
            }

            $this->logger->error($json, [
                'method' => $request->getMethod(),
                'uri' => (string)$request->getUri(),
            ]);

            return (new Response())->withStatus(500);
        }
    }
}