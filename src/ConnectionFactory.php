<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Connection\Connection;
use Soliant\SimpleFM\Connection\ConnectionInterface;
use Zend\Diactoros\Uri;

final class ConnectionFactory
{
    public function __invoke(ContainerInterface $container) : ConnectionInterface
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('connection')
        ;

        $identityHandler = null;
        $logger = null;

        if ($config->hasNonNullValue('identity_handler')) {
            $identityHandler = $container->get($config->getString('identity_handler'));
        }

        if ($config->hasNonNullValue('logger')) {
            $logger = $container->get($config->getString('logger'));
        }

        $uri = new Uri($config->getString('uri'));

        if ($config->hasNonNullValue('username')) {
            $uri->withUserInfo(
                $config->getString('username'),
                $config->hasNonNullValue('password') ? $config->getString('password') : null
            );
        }

        return new Connection(
            $container->get($config->getString('http_client')),
            $uri,
            $config->getString('database'),
            $identityHandler,
            $logger
        );
    }
}
