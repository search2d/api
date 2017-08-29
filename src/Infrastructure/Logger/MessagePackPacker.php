<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Logger;

use Fluent\Logger\Entity;
use Fluent\Logger\PackerInterface;
use MessagePack\Packer;

class MessagePackPacker implements PackerInterface
{
    /** @var \MessagePack\Packer */
    private $packer;

    public function __construct()
    {
        $this->packer = new Packer();
    }

    /**
     * @param \Fluent\Logger\Entity $entity
     * @return string
     */
    public function pack(Entity $entity)
    {
        return $this->packer->pack([$entity->getTag(), $entity->getTime(), $entity->getData()]);
    }
}