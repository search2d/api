<?php
declare(strict_types=1);

namespace Search2d\Provider\Presentation\Command;

use League\Tactician\CommandBus;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Search2d\Presentation\Cli\Command\Pixiv\RequestIllustCommand;
use Search2d\Presentation\Cli\Command\Pixiv\RequestIllustWorkerCommand;
use Search2d\Presentation\Cli\Command\Pixiv\RequestRankingCommand;
use Search2d\Presentation\Cli\Command\Pixiv\RequestRankingWorkerCommand;
use Symfony\Component\Console\Application;

class PixivServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container->extend(Application::class, function (Application $app, Container $container) {
            $commandBus = $container[CommandBus::class];
            $app->addCommands([
                new RequestIllustCommand($commandBus),
                new RequestIllustWorkerCommand($commandBus),
                new RequestRankingCommand($commandBus),
                new RequestRankingWorkerCommand($commandBus),
            ]);
            return $app;
        });
    }
}