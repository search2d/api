<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Usecase;

use Doctrine\DBAL\Connection;
use League\Tactician\Middleware;

class TransactionMiddleware implements Middleware
{
    /** @var \Doctrine\DBAL\Connection */
    private $conn;

    /**
     * @param \Doctrine\DBAL\Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     * @throws \Exception
     */
    public function execute($command, callable $next)
    {
        $this->conn->beginTransaction();
        try {
            $result = $next($command);
            $this->conn->commit();
            return $result;
        } catch (\Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
}