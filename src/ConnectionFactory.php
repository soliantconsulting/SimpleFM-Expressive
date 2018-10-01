<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use DateTimeZone;
use Psr\Container\ContainerInterface;
use Soliant\SimpleFM\Client\Connection;

final class ConnectionFactory
{
    public function __invoke(ContainerInterface $container) : Connection
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('connection');

        return new Connection(
            $config->getString('base_uri'),
            $config->getString('username'),
            $config->getString('password'),
            $config->getString('database'),
            new DateTimeZone($config->getString('time_zone'))
        );
    }
}
