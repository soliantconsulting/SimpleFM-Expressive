<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use DateTimeZone;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClient;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;

final class ResultSetClientFactory
{
    public function __invoke(ContainerInterface $container) : ResultSetClientInterface
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('result_set_client')
        ;

        return new ResultSetClient(
            $container->get(ConnectionInterface::class),
            new DateTimeZone($config->getString('time_zone'))
        );
    }
}
