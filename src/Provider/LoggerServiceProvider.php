<?php
declare(strict_types=1);

namespace Search2d\Provider;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\JsonFormatter;
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
            $config = $container['config']->logger;

            $formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, false);
            $formatter->includeStacktraces();

            $handler = new CloudWatch(
                new CloudWatchLogsClient(['version' => '2014-03-28', 'region' => $config->cwl->region]),
                $config->cwl->group,
                $config->cwl->stream,
                $config->cwl->retention_days,
                $config->cwl->batch_size
            );
            $handler->setFormatter($formatter);

            $logger = new Logger($config->name);
            $logger->pushHandler($handler);

            return $logger;
        };
    }
}