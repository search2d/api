<?php
declare(strict_types=1);

namespace Search2d\Usecase\Pixiv;

use League\Tactician\CommandBus;
use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RemoteRepository;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Domain\Pixiv\RequestIllustReceiver;
use Search2d\Domain\Search\Detail;
use Search2d\Usecase\Search\IndexCommand;

class HandleRequestIllustHandler
{
    /** @var  \Search2d\Domain\Pixiv\RequestIllustReceiver */
    private $requestIllustReceiver;

    /** @var \Search2d\Domain\Pixiv\RemoteRepository */
    private $remoteRepository;

    /** @var \League\Tactician\CommandBus */
    private $commandBus;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Search2d\Domain\Pixiv\RequestIllustReceiver $requestIllustReceiver
     * @param \Search2d\Domain\Pixiv\RemoteRepository $remoteRepository
     * @param \League\Tactician\CommandBus $commandBus
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        RequestIllustReceiver $requestIllustReceiver,
        RemoteRepository $remoteRepository,
        CommandBus $commandBus,
        LoggerInterface $logger
    )
    {
        $this->requestIllustReceiver = $requestIllustReceiver;
        $this->remoteRepository = $remoteRepository;
        $this->commandBus = $commandBus;
        $this->logger = $logger;
    }

    /**
     * @param \Search2d\Usecase\Pixiv\HandleRequestIllustCommand $command
     * @return void
     */
    public function __invoke(HandleRequestIllustCommand $command): void
    {
        $this->requestIllustReceiver->receive(function (RequestIllust $request): bool {
            try {
                $this->logger->info('イラスト取得リクエストの処理を開始', [
                    'request' => $request,
                ]);
                $this->processRequestIllust($request);
            } catch (\Exception $exception) {
                $this->logger->error('イラスト取得リクエストの処理に失敗', [
                    'request' => $request,
                    'exception' => $exception,
                ]);
                return false;
            }

            return true;
        });
    }

    /**
     * @param \Search2d\Domain\Pixiv\RequestIllust $request
     * @return void
     */
    private function processRequestIllust(RequestIllust $request): void
    {
        $illust = $this->remoteRepository->getIllust($request->getIllustId());

        $detail = new Detail(
            $illust->getUrl(),
            $illust->getTitle(),
            $illust->getCaption(),
            $illust->getCreated(),
            $illust->getUserUrl(),
            $illust->getUserName(),
            $illust->getUserBiog()
        );

        /** @var \Search2d\Domain\Pixiv\IllustPage $page */
        foreach ($illust->getPages() as $page) {
            $image = $this->remoteRepository->getImage($page->getImageUrl());

            $this->commandBus->handle(new IndexCommand($image, $detail));
        }
    }
}