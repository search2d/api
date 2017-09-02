<?php
declare(strict_types=1);

namespace Search2d\Presentation\Api\Action\Api;

use Aura\Filter\FilterFactory;
use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Domain\Search\Sha1;
use Search2d\Presentation\Api\Helper;
use Search2d\Usecase\Search\QueriedImageNotFoundException;
use Search2d\Usecase\Search\SearchCommand;

class SearchAction
{
    private const SEARCH_RADIUS = 5;
    private const SEARCH_COUNT = 10;

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
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $filter = (new FilterFactory())->newSubjectFilter();
        $filter->validate('sha1')->is('regex', '/^[a-zA-Z0-9]{40}$/');
        if (!$filter->apply($args)) {
            return $this->helper->responseFailure($response, 400, $filter->getFailures()->getMessagesAsString());
        }

        try {
            /** @var \Search2d\Domain\Search\ResultCollection $results */
            $results = $this->commandBus->handle(
                new SearchCommand(new Sha1($args['sha1']), self::SEARCH_RADIUS, self::SEARCH_COUNT)
            );
        } catch (QueriedImageNotFoundException $e) {
            return $this->helper->responseFailure($response, 404, '指定されたSHA1に対応する画像が存在しません');
        }

        $data = [];
        /** @var \Search2d\Domain\Search\Result $result */
        foreach ($results as $result) {
            $data[] = [
                'distance' => $result->getDistance(),
                'image_url' => $result->getIndexedImage()->getImageUrl(),
                'page_url' => $result->getIndexedImage()->getPageUrl(),
                'page_title' => $result->getIndexedImage()->getPageTitle(),
                'crawled_at' => $result->getIndexedImage()->getCrawledAt(),
            ];
        }

        return $this->helper->responseSuccess($response, 200, $data);
    }
}