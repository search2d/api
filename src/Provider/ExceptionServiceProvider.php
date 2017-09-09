<?php
declare(strict_types=1);

namespace Search2d\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Search2d\Infrastructure\ExceptionHandler;

class ExceptionServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[ExceptionHandler::class] = function (Container $container) {
            return new ExceptionHandler($container[LoggerInterface::class]);
        };
    }
}