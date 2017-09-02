<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Logger;

use Psr\Log\LoggerInterface;

class ExceptionHandler
{
    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        set_exception_handler([$this, 'handle']);
    }

    /**
     * @param \Throwable $throwable
     * @return void
     */
    public function handle(\Throwable $throwable): void
    {
        $this->logger->error($throwable->getMessage(), ['exception' => $throwable]);
    }
}