<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Client\Layout\LayoutClient;
use Soliant\SimpleFM\Client\Layout\LayoutClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;

final class LayoutClientFactory
{
    public function __invoke(ContainerInterface $container) : LayoutClientInterface
    {
        return new LayoutClient(
            $container->get(ConnectionInterface::class)
        );
    }
}
