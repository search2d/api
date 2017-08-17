<?php
declare(strict_types=1);

namespace Search2d\Test;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Faker\Factory;
use Faker\Generator;
use Psr\Http\Message\ResponseInterface;
use Search2d\Container;
use Slim\App;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Uri;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function call(string $method, string $uri): ResponseInterface
    {
        $this->container['request'] = function () use ($method, $uri) {
            return new Request(
                $method,
                Uri::createFromString($uri),
                new Headers(),
                [],
                [],
                stream_for(''),
                []
            );
        };

        /** @var \Slim\App $app */
        $app = $this->container[App::class];

        return $app->run(true);
    }
}