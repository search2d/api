<?php
declare(strict_types=1);

namespace Search2d\Provider;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;

class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[LoggerInterface::class] = function (Container $container) {
            $config = $container['config'];
            $logger = new Logger($config->logger->name);
            $logger->pushHandler(new StreamHandler($config->logger->path, $config->logger->level));
            return $logger;
        };
    }
}