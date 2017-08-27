<?php
declare(strict_types=1);

namespace Search2d\Provider\Domain;

use Aws\Sqs\SqsClient;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RemoteRepository;
use Search2d\Domain\Pixiv\RequestIllustReceiver;
use Search2d\Domain\Pixiv\RequestIllustSender;
use Search2d\Domain\Pixiv\RequestRankingReceiver;
use Search2d\Domain\Pixiv\RequestRankingSender;
use Search2d\Infrastructure\Domain\Pixiv\ApiClient;
use Search2d\Infrastructure\Domain\Pixiv\ApiRemoteRepository;
use Search2d\Infrastructure\Domain\Pixiv\QueueRequestIllustReceiver;
use Search2d\Infrastructure\Domain\Pixiv\QueueRequestIllustSender;
use Search2d\Infrastructure\Domain\Pixiv\QueueRequestRankingReceiver;
use Search2d\Infrastructure\Domain\Pixiv\QueueRequestRankingSender;
use Search2d\Infrastructure\Domain\SqsReceiver;
use Search2d\Infrastructure\Domain\SqsSender;

class PixivServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[RemoteRepository::class] = function (Container $container) {
            $pixiv = $container['config']->pixiv;

            $options = [
                RequestOptions::DELAY => $pixiv->api->delay,
                RequestOptions::TIMEOUT => $pixiv->api->timeout,
                RequestOptions::CONNECT_TIMEOUT => $pixiv->api->connection_timeout,
            ];

            if ($pixiv->api->proxy) {
                $options[RequestOptions::PROXY] = $pixiv->api->proxy;
            }

            $apiClient = new ApiClient(
                new Client($options),
                $pixiv->api->username,
                $pixiv->api->password,
                $pixiv->api->client_id,
                $pixiv->api->client_secret,
                $container[LoggerInterface::class]
            );

            return new ApiRemoteRepository($apiClient, $container[LoggerInterface::class]);
        };

        $container['pixiv.sqs_client'] = function (Container $container) {
            $pixiv = $container['config']->pixiv;
            return new SqsClient([
                'version' => '2012-11-05',
                'region' => $pixiv->sqs->region,
            ]);
        };

        $container[RequestIllustReceiver::class] = function (Container $container) {
            $pixiv = $container['config']->pixiv;
            return new QueueRequestIllustReceiver(
                new SqsReceiver(
                    $container['pixiv.sqs_client'],
                    $pixiv->sqs->request_illust_url,
                    $container[LoggerInterface::class]
                ),
                $container[LoggerInterface::class]
            );
        };

        $container[RequestIllustSender::class] = function (Container $container) {
            $pixiv = $container['config']->pixiv;
            return new QueueRequestIllustSender(
                new SqsSender(
                    $container['pixiv.sqs_client'],
                    $pixiv->sqs->request_illust_url,
                    $container[LoggerInterface::class]
                ),
                $container[LoggerInterface::class]
            );
        };

        $container[RequestRankingReceiver::class] = function (Container $container) {
            $pixiv = $container['config']->pixiv;
            return new QueueRequestRankingReceiver(
                new SqsReceiver(
                    $container['pixiv.sqs_client'],
                    $pixiv->sqs->request_ranking_url,
                    $container[LoggerInterface::class]
                ),
                $container[LoggerInterface::class]
            );
        };

        $container[RequestRankingSender::class] = function (Container $container) {
            $pixiv = $container['config']->pixiv;
            return new QueueRequestRankingSender(
                new SqsSender(
                    $container['pixiv.sqs_client'],
                    $pixiv->sqs->request_ranking_url,
                    $container[LoggerInterface::class]
                ),
                $container[LoggerInterface::class]
            );
        };
    }
}