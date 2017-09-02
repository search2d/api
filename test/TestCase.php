<?php
declare(strict_types=1);

namespace Search2d\Test;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Faker\Factory;
use Faker\Generator;
use Psr\Http\Message\ResponseInterface;
use Search2d\Container;
use Search2d\Infrastructure\Presentation\Api\Frontend;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use function GuzzleHttp\Psr7\stream_for;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Search2d\Container */
    protected $container;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->container = new Container(__DIR__ . '/..');
    }

    /**
     * @return void
     */
    protected function refreshDatabase(): void
    {
        $this->migrate('first');
        $this->migrate('latest');
    }

    /**
     * @param string $version
     * @return void
     */
    private function migrate(string $version): void
    {
        /** @var \Symfony\Component\Console\Application $cli */
        $cli = $this->container[Application::class];

        $command = $cli->find('migrations:migrate');

        $input = new ArrayInput([
            'command' => 'migrations:migrate',
            'version' => $version,
        ]);
        $input->setInteractive(false);

        $returnCode = $command->run($input, new NullOutput());
        if ($returnCode !== 0) {
            throw new \RuntimeException();
        }
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function connection(): Connection
    {
        return $this->container[Connection::class];
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function queryBuilder(): QueryBuilder
    {
        return $this->connection()->createQueryBuilder();
    }

    /**
     * @return \Faker\Generator
     */
    protected function faker(): Generator
    {
        $faker = Factory::create();
        $faker->addProvider(new FakerProvider($faker));
        return $faker;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string|array|object $body
     * @param array $headers
     * @param array $files
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function call(string $method, string $uri, $body = '', array $headers = [], array $files = []): ResponseInterface
    {
        if (is_array($body) || is_object($body)) {
            $body = json_encode($body);
            if ($body === false) {
                throw new \RuntimeException(json_last_error_msg(), json_last_error());
            }
            $headers['Content-Type'] = 'application/json;charset=utf-8';
        }

        $request = new ServerRequest([], $files, $uri, $method, stream_for($body), $headers);

        /** @var \Search2d\Infrastructure\Presentation\Api\Frontend $frontend */
        $frontend = $this->container[Frontend::class];
        return $frontend->handle($request, new Response());
    }
}