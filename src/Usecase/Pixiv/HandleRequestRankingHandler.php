<?php
declare(strict_types=1);

namespace Search2d\Usecase\Pixiv;

use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RemoteRepository;
use Search2d\Domain\Pixiv\RequestIllust;
use Search2d\Domain\Pixiv\RequestIllustSender;
use Search2d\Domain\Pixiv\RequestRanking;
use Search2d\Domain\Pixiv\RequestRankingReceiver;

class HandleRequestRankingHandler
{
    /** @var  \Search2d\Domain\Pixiv\RequestRankingReceiver */
    private $requestRankingReceiver;

    /** @var \Search2d\Domain\Pixiv\RequestIllustSender */
    private $requestIllustSender;

    /** @var \Search2d\Domain\Pixiv\RemoteRepository */
    private $remoteRepository;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Search2d\Domain\Pixiv\RequestRankingReceiver $requestRankingReceiver
     * @param \Search2d\Domain\Pixiv\RequestIllustSender $requestIllustSender
     * @param \Search2d\Domain\Pixiv\RemoteRepository $remoteRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        RequestRankingReceiver $requestRankingReceiver,
        RequestIllustSender $requestIllustSender,
        RemoteRepository $remoteRepository,
        LoggerInterface $logger
    )
    {
        $this->requestRankingReceiver = $requestRankingReceiver;
        $this->requestIllustSender = $requestIllustSender;
        $this->remoteRepository = $remoteRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Search2d\Usecase\Pixiv\HandleRequestRankingCommand $command
     * @return void
     */
    public function handle(HandleRequestRankingCommand $command): void
    {
        $this->requestRankingReceiver->receive(function (RequestRanking $request): bool {
            try {
                $this->processRequestRanking($request);
            } catch (\Exception $exception) {
                $this->logger->error('ランキング取得リクエストの処理に失敗', [
                    'request' => $request,
                    'exception' => $exception,
                ]);
                return false;
            }
            return true;
        });
    }

    /**
     * @param \Search2d\Domain\Pixiv\RequestRanking $request
     * @return void
     */
    private function processRequestRanking(RequestRanking $request): void
    {
        $this->logger->info('ランキング取得リクエストの処理を開始', [
            'request' => $request,
        ]);

        $ranking = $this->remoteRepository->getRanking(
            $request->getMode(),
            $request->getDate()
        );

        /** @var \Search2d\Domain\Pixiv\RankingIllust $illust */
        foreach ($ranking->getIllusts() as $illust) {
            $this->requestIllustSender->send(
                new RequestIllust($illust->getIllustId())
            );
        }
    }
}