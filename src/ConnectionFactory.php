<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use Assert\Assertion;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Connection\Connection;
use Zend\Diactoros\Uri;

final class ConnectionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        Assertion::isArrayAccessible($config);
        Assertion::keyExists($config, 'simplefm');

        $simpleFmConfig = $config['simplefm'];
        Assertion::isArrayAccessible($simpleFmConfig);
        Assertion::keyExists($simpleFmConfig, 'connection');

        $connectionConfig = $simpleFmConfig['connection'];
        Assertion::isArrayAccessible($connectionConfig);

        Assertion::keyExists('uri', $connectionConfig);
        Assertion::keyExists('database', $connectionConfig);

        $identityHandler = null;
        $logger = null;

        if (isset($connectionConfig['identity_handler'])) {
            $identityHandler = $container->get($connectionConfig['identity_handler']);
        }

        if (isset($connectionConfig['logger'])) {
            $logger = $container->get($connectionConfig['logger']);
        }

        return new Connection(
            $container->get($connectionConfig['http_client'] ?? 'soliant.simplefm.expressive.http-client'),
            new Uri($connectionConfig['uri']),
            $connectionConfig['database'],
            $identityHandler,
            $logger
        );
    }
}
