<?php
declare(strict_types=1);

namespace Search2d\Provider;

use Fluent\Logger\FluentLogger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Search2d\Infrastructure\Logger\FluentHandler;
use Search2d\Infrastructure\Logger\MessagePackPacker;

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

            $logger = new Logger($config->name);

            if ($config->fluent->enabled) {
                $formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, false);
                $formatter->includeStacktraces();

                $fluent = new FluentLogger($config->fluent->host, $config->fluent->port);
                $fluent->setPacker(new MessagePackPacker());

                $handler = new FluentHandler(
                    $fluent,
                    $config->fluent->tag,
                    $config->fluent->log_group_name,
                    $config->fluent->uid_path,
                    Logger::toMonologLevel($config->fluent->level)
                );
                $handler->setFormatter($formatter);

                $logger->pushHandler($handler);
            }

            if ($config->stream->enabled) {
                $formatter = new LineFormatter();
                $formatter->includeStacktraces();

                $handler = new StreamHandler(
                    $config->stream->path,
                    Logger::toMonologLevel($config->stream->level)
                );
                $handler->setFormatter($formatter);

                $logger->pushHandler($handler);
            }

            return $logger;
        };
    }
}