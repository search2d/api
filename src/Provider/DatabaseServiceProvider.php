<?php
declare(strict_types=1);

namespace Search2d\Provider;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[Connection::class] = function (Container $container) {
            $config = $container['config'];
            return DriverManager::getConnection(['url' => $config->database->dsn,], new Configuration());
        };
    }
}