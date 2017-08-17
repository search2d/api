<?php
declare(strict_types=1);

namespace Search2d;

use Slim\Container as SlimContainer;
use Zend\Config\Config;

class Container extends SlimContainer
{
    /** @var array */
    private $providers = [
        Provider\LoggerServiceProvider::class,
        Provider\DatabaseServiceProvider::class,
        Provider\CommandBusServiceProvider::class,

        Provider\Domain\PixivServiceProvider::class,
        Provider\Domain\SearchServiceProvider::class,

        Provider\Usecase\PixivServiceProvider::class,
        Provider\Usecase\SearchServiceProvider::class,

        Provider\Presentation\ApiServiceProvider::class,
        Provider\Presentation\CliServiceProvider::class,
        Provider\Presentation\Command\PixivServiceProvider::class,
        Provider\Presentation\Command\MigrationsServiceProvider::class,
    ];

    /**
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        $config = new Config(require __DIR__ . '/config.php');

        parent::__construct(['config' => $config]);

        $this->registerServiceProviders();
    }

    /**
     * @return void
     */
    private function registerServiceProviders(): void
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider);
        }
    }
}