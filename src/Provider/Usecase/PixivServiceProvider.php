<?php
declare(strict_types=1);

namespace Search2d\Provider\Usecase;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Search2d\Domain\Pixiv\RemoteRepository;
use Search2d\Domain\Pixiv\RequestIllustReceiver;
use Search2d\Domain\Pixiv\RequestIllustSender;
use Search2d\Domain\Pixiv\RequestRankingReceiver;
use Search2d\Domain\Pixiv\RequestRankingSender;
use Search2d\Infrastructure\Usecase\CommandMapper;
use Search2d\Usecase\Pixiv\HandleRequestIllustCommand;
use Search2d\Usecase\Pixiv\HandleRequestIllustHandler;
use Search2d\Usecase\Pixiv\HandleRequestRankingCommand;
use Search2d\Usecase\Pixiv\HandleRequestRankingHandler;
use Search2d\Usecase\Pixiv\SendRequestIllustCommand;
use Search2d\Usecase\Pixiv\SendRequestIllustHandler;
use Search2d\Usecase\Pixiv\SendRequestRankingCommand;
use Search2d\Usecase\Pixiv\SendRequestRankingHandler;
use Search2d\Usecase\Search\IndexHandler;

class PixivServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[SendRequestIllustHandler::class] = function (Container $container) {
            return new SendRequestIllustHandler(
                $container[RequestIllustSender::class]
            );
        };

        $container[HandleRequestIllustHandler::class] = function (Container $container) {
            return new HandleRequestIllustHandler(
                $container[RequestIllustReceiver::class],
                $container[RemoteRepository::class],
                $container[IndexHandler::class],
                $container[LoggerInterface::class]
            );
        };

        $container[SendRequestRankingHandler::class] = function (Container $container) {
            return new SendRequestRankingHandler(
                $container[RequestRankingSender::class]
            );
        };

        $container[HandleRequestRankingHandler::class] = function (Container $container) {
            return new HandleRequestRankingHandler(
                $container[RequestRankingReceiver::class],
                $container[RequestIllustSender::class],
                $container[RemoteRepository::class],
                $container[LoggerInterface::class]
            );
        };

        $container->extend(CommandMapper::class, function (CommandMapper $mapper, Container $_) {
            $mapper->addMapping([
                SendRequestIllustCommand::class => SendRequestIllustHandler::class,
                HandleRequestIllustCommand::class => HandleRequestIllustHandler::class,
                SendRequestRankingCommand::class => SendRequestRankingHandler::class,
                HandleRequestRankingCommand::class => HandleRequestRankingHandler::class,
            ]);
            return $mapper;
        });
    }
}