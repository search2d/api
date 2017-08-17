<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain;

interface QueueReceiver
{
    /**
     * @param callable $callback
     * @return void
     * @throws \Throwable
     */
    public function receive(callable $callback): void;
}