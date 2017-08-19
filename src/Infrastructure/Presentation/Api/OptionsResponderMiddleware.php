<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Presentation\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OptionsResponderMiddleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if ($request->getMethod() === 'OPTIONS') {
            return $response->withStatus(204);
        }

        return $next($request, $response);
    }
}