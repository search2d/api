<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Usecase;

use Pimple\Container;

class CommandMapper
{
    /** @var \Pimple\Container */
    private $container;

    /** @var array */
    private $mapping;

    /**
     * @param \Pimple\Container $container
     * @param array $mapping
     */
    public function __construct(Container $container, array $mapping = [])
    {
        $this->container = $container;
        $this->mapping = $mapping;
    }

    /**
     * @param string $commandName
     * @return object
     */
    public function __invoke(string $commandName)
    {
        if (!isset($this->mapping[$commandName])) {
            return null;
        }

        $handlerName = $this->mapping[$commandName];

        if (!isset($this->container[$handlerName])) {
            return null;
        }

        return $this->container[$handlerName];
    }

    /**
     * @param array $mapping
     * @return void
     */
    public function addMapping(array $mapping): void
    {
        $this->mapping = array_merge($this->mapping, $mapping);
    }
}