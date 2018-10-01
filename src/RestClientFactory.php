<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use Psr\Container\ContainerInterface;
use Soliant\SimpleFM\Client\ClientInterface;
use Soliant\SimpleFM\Client\Connection;
use Soliant\SimpleFM\Client\RestClient;

final class RestClientFactory
{
    public function __invoke(ContainerInterface $container) : ClientInterface
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('client');

        return new RestClient(
            $container->get($config->getString('http_client')),
            $container->get(Connection::class)
        );
    }
}
