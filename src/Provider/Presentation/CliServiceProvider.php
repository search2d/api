<?php
declare(strict_types=1);

namespace Search2d\Provider\Presentation;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

class CliServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $container[Application::class] = function () {
            return new Application();
        };
    }
}