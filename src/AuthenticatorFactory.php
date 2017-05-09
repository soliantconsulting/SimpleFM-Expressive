<?php
declare(strict_types = 1);

namespace Soliant\SimpleFM\Expressive;

use DASPRiD\TreeReader\TreeReader;
use Interop\Container\ContainerInterface;
use Soliant\SimpleFM\Authentication\Authenticator;
use Soliant\SimpleFM\Client\ResultSet\ResultSetClientInterface;

final class AuthenticatorFactory
{
    public function __invoke(ContainerInterface $container) : Authenticator
    {
        $config = (new TreeReader($container->get('config'), 'config'))
            ->getChildren('simplefm')
            ->getChildren('authenticator')
        ;

        return new Authenticator(
            $container->get(ResultSetClientInterface::class),
            $container->get($config->getString('identity_handler', 'soliant.simplefm.expressive.identity-handler')),
            $config->getString('identity_layout'),
            $config->getString('username_field')
        );
    }
}
