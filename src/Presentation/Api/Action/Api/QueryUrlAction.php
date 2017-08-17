<?php
declare(strict_types=1);

namespace Search2d\Presentation\Api\Action\Api;

use Aura\Filter\FilterFactory;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Presentation\Api\Helper;
use Search2d\Usecase\Search\QueryUrlCommand;

class QueryUrlAction
{
    /** @var \League\Tactician\CommandBus */
    private $commandBus;

    /** @var \Search2d\Presentation\Api\Helper */
    private $helper;

    /**
     * @param \League\Tactician\CommandBus $commandBus
     * @param \Search2d\Presentation\Api\Helper $helper
     */
    public function __construct(CommandBus $commandBus, Helper $helper)
    {
        $this->commandBus = $commandBus;
        $this->helper = $helper;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = json_decode($request->getBody()->getContents());
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->helper->responseFailure($response, 403, json_last_error_msg());
        }

        $filter = (new FilterFactory())->newSubjectFilter();
        $filter->validate('url')->is('url');

        if (!$filter->apply($params)) {
            return $this->helper->responseFailure($response, 403, $filter->getFailures()->getMessagesAsString());
        }

        /** @var \Search2d\Domain\Search\QueriedImage $queriedImage */
        $queriedImage = $this->commandBus->handle(new QueryUrlCommand($params->url));

        $result = ['sha1' => $queriedImage->getSha1()->value];

        return $this->helper->responseSuccess($response, 201, $result);
    }
}