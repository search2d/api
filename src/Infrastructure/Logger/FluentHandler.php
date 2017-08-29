<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Logger;

use Fluent\Logger\FluentLogger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class FluentHandler extends AbstractProcessingHandler
{
    /** @var \Fluent\Logger\FluentLogger */
    private $fluentLogger;

    /** @var string */
    private $tag;

    /**
     * @param \Fluent\Logger\FluentLogger $fluentLogger
     * @param string $tag
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(FluentLogger $fluentLogger, string $tag, int $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->fluentLogger = $fluentLogger;
        $this->tag = $tag;
    }

    /**
     * @param array $record
     * @return void
     */
    protected function write(array $record): void
    {
        $this->fluentLogger->post($this->tag, ['message' => $record['formatted']]);
    }
}