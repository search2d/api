<?php
declare(strict_types=1);

namespace Search2d\Provider;

use Doctrine\DBAL\Connection;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\CallableLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Search2d\Infrastructure\Usecase\CommandMapper;
use Search2d\Infrastructure\Usecase\TransactionMiddleware;

class CommandBusServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[CommandMapper::class] = function (Container $container) {
            return new CommandMapper($container);
        };

        $container[CommandBus::class] = function (Container $container) {
            return new CommandBus([
                new TransactionMiddleware($container[Connection::class]),
                new CommandHandlerMiddleware(
                    new ClassNameExtractor(),
                    new CallableLocator($container[CommandMapper::class]),
                    new HandleInflector()
                ),
            ]);
        };
    }

}