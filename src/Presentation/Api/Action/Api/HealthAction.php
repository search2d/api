<?php
declare(strict_types=1);

namespace Search2d\Presentation\Api\Action\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Presentation\Api\Helper;

class HealthAction
{
    /** @var \Search2d\Presentation\Api\Helper */
    private $helper;

    /**
     * @param \Search2d\Presentation\Api\Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->helper->responseSuccess($response, 200, ['status' => 'OK']);
    }
}