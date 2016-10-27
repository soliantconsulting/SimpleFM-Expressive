<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use DateTimeZone;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClient;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;
use Soliant\SimpleFM\Connection\ConnectionInterface;

final class ResultSetClientFactory
{
    public function __invoke(ContainerInterface $container) : ResultSetClientInterface
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyExists($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyExists($simpleFmConfig, 'result_set_client');

        $resultSetClientConfig = $simpleFmConfig['result_set_client'];
        Assertion::isArrayAccessible($resultSetClientConfig);

        Assertion::keyExists($resultSetClientConfig, 'time_zone');

        return new ResultSetClient(
            $container->get(ConnectionInterface::class),
            new DateTimeZone($resultSetClientConfig['time_zone'])
        );
    }
}
